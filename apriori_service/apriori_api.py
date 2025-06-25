from flask import Flask, request, jsonify
import mysql.connector
import pandas as pd
from mlxtend.preprocessing import TransactionEncoder
from mlxtend.frequent_patterns import apriori, association_rules

app = Flask(__name__)

DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'penyewaan_alat'
}

def run_apriori(transactions_data, min_support_threshold, min_confidence_threshold):
    if not transactions_data:
        return {}, []

    te = TransactionEncoder()
    te_array = te.fit_transform(transactions_data)
    df_encoded = pd.DataFrame(te_array, columns=te.columns_)

    frequent_itemsets_df = apriori(df_encoded, min_support=min_support_threshold, use_colnames=True)

    frequent_itemsets_dict = {
        ','.join(list(row['itemsets'])): row['support']
        for index, row in frequent_itemsets_df.iterrows()
    }

    rules_df = association_rules(frequent_itemsets_df, metric="confidence", min_threshold=min_confidence_threshold)

    rules_df['rekomendasi_display'] = rules_df.apply(
        lambda row: f"Jika menyewa {' & '.join(list(row['antecedents']))}, maka cenderung menyewa {' & '.join(list(row['consequents']))}",
        axis=1
    )

    formatted_rules = []
    rules_to_format = rules_df.sort_values(
        by=['confidence', 'support', 'lift'],
        ascending=[False, False, False]
    )

    for index, row in rules_to_format.iterrows():
        formatted_rules.append({
            'antecedent': list(row['antecedents']),
            'consequent': list(row['consequents']),
            'support': row['support'],
            'confidence': row['confidence'],
            'lift': row['lift'],
            'rekomendasi_display': row['rekomendasi_display']
        })
    
    return frequent_itemsets_dict, formatted_rules

@app.route('/api/apriori', methods=['GET'])
def apriori_endpoint():
    try:
        min_support = float(request.args.get('min_support', 0.1))
        min_confidence = float(request.args.get('min_confidence', 0.5))

        conn = mysql.connector.connect(**DB_CONFIG)
        cursor = conn.cursor()

        transactions_for_apriori = []
        grouped_transactions_query = "SELECT `tanggal_transaksi`, GROUP_CONCAT(`nama_barang` SEPARATOR ',') AS items_in_basket " \
                                     "FROM transaksi " \
                                     "GROUP BY `tanggal_transaksi` " \
                                     "ORDER BY `tanggal_transaksi`"

        cursor.execute(grouped_transactions_query)
        
        for (tanggal_transaksi, items_in_basket_str) in cursor:
            items_array = [item.strip() for item in items_in_basket_str.split(',') if item.strip()]
            transactions_for_apriori.append(list(set(items_array)))

        cursor.close()
        conn.close()

        print(f"\n--- DEBUG INFO: Data Transaksi ---")
        print(f"Total Transactions fetched from DB: {len(transactions_for_apriori)}")
        if transactions_for_apriori:
            print(f"First 3 transactions: {transactions_for_apriori[:3]}")
        else:
            print("No transactions data retrieved from database.")
        print(f"Min Support used: {min_support}, Min Confidence used: {min_confidence}")
        print(f"-----------------------------------\n")

        if not transactions_for_apriori:
            return jsonify({
                "frequent_itemsets": {},
                "association_rules": [],
                "min_support": min_support,
                "min_confidence": min_confidence,
                "total_transactions": 0,
                "message": "Tidak ada data transaksi yang ditemukan di database Anda dengan kriteria ini."
            }), 200
            
        frequent_itemsets, association_rules = run_apriori(
            transactions_for_apriori, 
            min_support, 
            min_confidence
        )

        print(f"--- DEBUG INFO: Hasil Apriori ---")
        print(f"Frequent Itemsets found: {len(frequent_itemsets)}")
        print(f"Association Rules found: {len(association_rules)}")
        if frequent_itemsets:
            print(f"Sample Frequent Itemsets (first 3): {list(frequent_itemsets.items())[:3]}")
        if association_rules:
            print(f"Sample Association Rules (first 3): {association_rules[:3]}")
        print(f"-----------------------------------\n")

        return jsonify({
            "frequent_itemsets": frequent_itemsets,
            "association_rules": association_rules,
            "min_support": min_support,
            "min_confidence": min_confidence,
            "total_transactions": len(transactions_for_apriori)
        })

    except mysql.connector.Error as db_err:
        print(f"DATABASE ERROR: {db_err}")
        return jsonify({"error": f"Kesalahan Database: {str(db_err)}", "message": "Terjadi kesalahan pada koneksi atau query database."}), 500
    except Exception as e:
        print(f"GENERAL API ERROR: {e}")
        return jsonify({"error": str(e), "message": "Terjadi kesalahan internal pada server Apriori. Periksa log server."}), 500

if __name__ == '__main__':
    app.run(debug=True, host='127.0.0.1', port=5000)