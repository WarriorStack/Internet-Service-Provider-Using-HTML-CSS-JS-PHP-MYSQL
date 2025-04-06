<?php
session_start();
include 'db_connection.php';
$path = __DIR__ . '/../../admin/Reports/tcpdf/tcpdf.php';
if (!file_exists($path)) {
    die("TCPDF file not found: " . $path);
}
require_once $path;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
            throw new Exception("User session not set.");
        }

        $customerId = $_SESSION['id']; // Get customer ID from session

        // Validate and get POST data
        if (!isset($_POST['amount']) || empty($_POST['amount']) || !isset($_POST['planId']) || empty($_POST['planId'])) {
            throw new Exception("Required parameters missing.");
        }

        $planId = $_POST['planId'];  // Plan ID selected by user
        $amount = $_POST['amount'];  // Amount paid for the plan
        $transactionDetails = "Plan Purchase";
        $transactionStatus = "Success";  // Assuming success

        // Get customer's address
        $sql = "SELECT cAddress FROM rginfo WHERE cid = :customerId";
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute(['customerId' => $customerId])) {
            throw new Exception("Database error: Failed to fetch customer details.");
        }
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            throw new Exception("Customer not found.");
        }

        $userLocation = $customer['cAddress']; // Customer's location

        // Get available server resources in the user's location
        $sql = "SELECT serverId FROM server_resources WHERE status = 'active' AND location = :userLocation ORDER BY availableCapacity DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userLocation' => $userLocation]);
        $serverResource = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$serverResource) {
            // If no server found in user's location, search any available server
            $sql = "SELECT serverId FROM server_resources WHERE status = 'active' ORDER BY availableCapacity DESC LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $serverResource = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$serverResource) {
                throw new Exception("No available servers.");
            }
        }

        $serverId = $serverResource['serverId']; // Assign server to the user


        // Start transaction
        $pdo->beginTransaction();

        // Insert transaction record (only once)
        $sql = "INSERT INTO transactions (transAmount, transDetail, transDate, transStatus, transCustomerId) 
                VALUES (:transAmount, :transDetails, NOW(), :transStatus, :transCustomerId)";
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute([
            'transAmount' => $amount,
            'transDetails' => $transactionDetails,
            'transStatus' => $transactionStatus,
            'transCustomerId' => $customerId
        ])) {
            throw new Exception("Database error: Failed to insert transaction.");
        }

        $transactionId = $pdo->lastInsertId(); // Get inserted transaction ID

        // Update accountInfo table: Assign the new plan to the user
        $sql = "UPDATE accountInfo 
                SET planId = :planId, planactivationdate = NOW(), serverId = :serverId 
                WHERE cId = :cId";
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute([
            'planId' => $planId,
            'serverId' => $serverId,
            'cId' => $customerId
        ])) {
            throw new Exception("Database error: Failed to update account information.");
        }

        if ($stmt->rowCount() === 0) {
            throw new Exception("No account updated. Ensure customer ID exists in accountInfo.");
        }

        // Update server resources (reduce available capacity)
        $sql = "UPDATE server_resources SET usedCapacity = usedCapacity + 1, availableCapacity = availableCapacity - 1 
                WHERE serverId = :serverId AND status = 'active'";
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute(['serverId' => $serverId])) {
            throw new Exception("Database error: Failed to update server resources.");
        }

        if ($stmt->rowCount() === 0) {
            throw new Exception("Server update failed. Ensure server ID exists.");
        }

        // Generate invoice and get path
        $invoicePath = generateInvoice($customerId, $planId, $amount, $transactionId);

        // Update invoice path in transactions table
        $sql = "UPDATE transactions SET invoicePath = :invoicePath WHERE transId = :transId";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'invoicePath' => $invoicePath,
            'transId' => $transactionId
        ]);

        // Commit transaction
        $pdo->commit();

        echo "Transaction successful! Plan assigned, server updated, and invoice generated.";
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log("Transaction Error: " . $e->getMessage());
        echo "Error: " . $e->getMessage();
    }
}

// Function to generate invoice
function generateInvoice($customerId, $planId, $amount, $transactionId)
{
    global $pdo;

    // Fetch customer details
    $sql = "SELECT cName, cEmail, cAddress FROM rginfo WHERE cid = :customerId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['customerId' => $customerId]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        throw new Exception("Customer details not found.");
    }

    // Fetch plan details
    $sql = "SELECT planName, planSpeed, planValidity FROM plans WHERE planId = :planId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['planId' => $planId]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$plan) {
        throw new Exception("Plan details not found.");
    }

    // Create PDF invoice
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('Helvetica', '', 12);

    $html = "
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .invoice-container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h3 { color: #007BFF; }
        hr { border: 1px solid #ddd; }
        .company-details, .footer { text-align: center; margin-bottom: 20px; }
        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .invoice-table th, .invoice-table td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .invoice-table th { background: #007BFF; color: #fff; }
        .amount { font-size: 20px; font-weight: bold; color: #28A745; }

        .invoice-table th, .invoice-table td { 
    padding: 12px; 
    border: 1px solid #ccc; 
    text-align: center;
}

h1, h3 { 
    color: #007BFF; 
    text-align: center;
}

.amount { 
    font-size: 22px; 
    font-weight: bold; 
    color: #28A745; 
    text-align: center;
}

    </style>

    <div class='invoice-container'>
        <div class='company-details'>
            <h2>SpeedNet ISP</h2>
            <p>Modern College of Arts, Science & Commerce Ganesh Khind Road, opposite Univercity Gate-53, Ganeshkhind, Pune, Maharashtra 411053</p>
            <p>Email: speedNet@gmail.com | Phone: +91 98765 43210</p>
        </div>
        
        <h1>Invoice</h1>
        <p><strong>Transaction ID:</strong> $transactionId</p>
        <p><strong>Date:</strong> " . date('Y-m-d H:i:s') . "</p>
        <hr>

        <h3>Customer Details</h3>
        <table class='invoice-table'>
            <tr><th>Name</th><td>{$customer['cName']}</td></tr>
            <tr><th>Email</th><td>{$customer['cEmail']}</td></tr>
            <tr><th>Address</th><td>{$customer['cAddress']}</td></tr>
        </table>
        
        <h3>Plan Details</h3>
        <table class='invoice-table'>
            <tr><th>Plan Name</th><td>{$plan['planName']}</td></tr>
            <tr><th>Speed</th><td>{$plan['planSpeed']}</td></tr>
            <tr><th>Validity</th><td>{$plan['planValidity']} days</td></tr>
        </table>

        <h3>Amount Paid</h3>
        <p class='amount'> ₹ $amount</p>
        
        <hr>
        <div class='footer'>
            <p>Thank you for choosing Your ISP Company.</p>
            <p>For any support, contact us at <strong>support@yourisp.com</strong></p>
        </div>
    </div>
";


    $pdf->writeHTML($html, true, false, true, false, '');

    // Save PDF
    $invoicePath = 'E:/Software/XAAMP Server/htdocs/ISPProject/admin/Reports/invoices/invoice_' . $transactionId . '.pdf';
    $pdf->Output($invoicePath, 'F');

    return $invoicePath;
}
