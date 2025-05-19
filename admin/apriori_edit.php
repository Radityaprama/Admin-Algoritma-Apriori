<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $itemset = mysqli_real_escape_string($conn, $_POST['itemset']);
    $support = (float) $_POST['support'];
    $confidence = (float) $_POST['confidence'];

    $query = "UPDATE apriori SET itemset='$itemset', support=$support, confidence=$confidence WHERE id=$id";
    mysqli_query($conn, $query);
}

header('Location: data_apriori.php');
exit();
?>
