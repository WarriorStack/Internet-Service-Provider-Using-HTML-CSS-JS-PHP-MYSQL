<?php
include 'db_connection.php';

if (isset($_GET['transactionId'])) {
    $transactionId = $_GET['transactionId'];

    // Retrieve the invoice path from the transaction table
    $sql = "SELECT invoicePath FROM transactions WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$transactionId]);
    $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($transaction && file_exists($transaction['invoicePath'])) {
        // Output the PDF to the browser for download
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($transaction['invoicePath']) . '"');
        readfile($transaction['invoicePath']);
    } else {
        echo "Invoice not found.";
    }
} else {
    echo "Transaction ID is required.";
}
?>
