<?php
session_start();
include('phps/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/ISPProject/loginPage.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch transaction history
try {
    $query = "SELECT * FROM transactions WHERE TransCustomerId = :user_id ORDER BY TransDate DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="stylesheet" href="transaction.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">
        <main>
            <h1>Transaction Page</h1>
            <div class="transaction-section">
                <!-- <div class="transaction-form">
                    <h3>Initiate Transaction</h3>
                    <form action="phps/submit_transaction.php" method="POST">
                        <input type="number" name="amount" placeholder="Enter amount" required>
                        <button type="submit" class="submit-btn">Submit Transaction</button>
                    </form>
                </div> -->

                <div class="transaction-history">
                    <h3>Your Transaction History</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Details</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($transactions) > 0) {
                                foreach ($transactions as $row) {
                                    echo "<tr>
                <td>" . $row['TransId'] . "</td>
                <td>" . $row['TransDate'] . "</td>
                <td>" . (isset($row['transAmount']) ? number_format((float)$row['transAmount'], 2) : '0.00') . "</td>
                <td>" . $row['TransStatus'] . "</td>
                <td>" . $row['TransDetail'] . "</td>
                <td>";

                                    // Add invoice download button if invoicePath exists
                                    if (!empty($row['invoicePath'])) {
                                        $invoiceFile = basename($row['invoicePath']); // Extract only the filename
                                        echo "<a href='phps/download_invoice.php?invoice=" . urlencode($invoiceFile) . "' target='_blank' class='download-btn'>Download</a>";
                                    } else {
                                        echo "<span class='no-invoice'>No Invoice</span>";
                                    }

                                    echo "</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No transactions found</td></tr>";
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </main>
    </div>
    <div id="toast" class="toast"></div>
    <script>
        window.addEventListener('load', () => {
            document.querySelector('main').classList.add('loaded');
            document.querySelectorAll('.transaction-form, .transaction-history').forEach(el => {
                el.classList.add('loaded');
            });

            <?php if (isset($_SESSION['success'])): ?>
                showToast("<?php echo $_SESSION['success']; ?>", "success");
                <?php unset($_SESSION['success']); ?>
            <?php elseif (isset($_SESSION['error'])): ?>
                showToast("<?php echo $_SESSION['error']; ?>", "error");
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        });

        function showToast(message, type) {
            var toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "toast show " + type;
            setTimeout(function() {
                toast.className = toast.className.replace("show", "");
            }, 3000);
        }
    </script>
    <?php include '../customer/css/dashboard.php' ?>
</body>

</html>