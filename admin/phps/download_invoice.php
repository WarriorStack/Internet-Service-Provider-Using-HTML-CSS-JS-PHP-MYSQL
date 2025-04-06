<?php
if (isset($_GET['invoice'])) {
    $invoiceFile = basename($_GET['invoice']); // Security: prevent path traversal
    $filePath = '../../admin/Reports/invoices/' . $invoiceFile; // Correct file path

    if (file_exists($filePath)) {
        // Set headers for file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $invoiceFile . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Output the file
        readfile($filePath);
        exit;
    } else {
        echo "Error: File not found!";
    }
} else {
    echo "Error: No file specified!";
}
?>
