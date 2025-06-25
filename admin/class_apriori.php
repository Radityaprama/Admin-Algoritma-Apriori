<?php
class Apriori {
    public $support = 0.5;
    public $confidence = 0.5;
    private $rules = [];

    public function __construct() {
        // Konstruktor kosong, bisa dikembangin
    }

    public function process($dataset) {
        // Dummy process: lo bisa ganti ini sama logic Apriori beneran
        $this->rules = [];

        foreach ($dataset as $transaction) {
            for ($i = 0; $i < count($transaction); $i++) {
                for ($j = $i + 1; $j < count($transaction); $j++) {
                    $item1 = $transaction[$i];
                    $item2 = $transaction[$j];
                    $this->rules[] = [
                        'item1' => $item1,
                        'item2' => $item2,
                        'support' => $this->support,
                        'confidence' => $this->confidence
                    ];
                }
            }
        }
    }

    public function getRules() {
        return $this->rules;
    }
}
?>
