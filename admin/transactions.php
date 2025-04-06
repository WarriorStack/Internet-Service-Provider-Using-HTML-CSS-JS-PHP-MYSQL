<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <link rel="stylesheet" href="inquiry.css">
    <link rel="stylesheet" href="transactions.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>

        <main>
            <h1>Transaction</h1>

            <!-- Filter Section -->
            <div class="filter-container">
                <select id="filterColumn">
                    <option value="all">All</option>
                    <option value="1">Transaction ID</option>
                    <option value="2">Amount</option>
                    <option value="3">Details</option>
                    <option value="4">Date</option>
                    <option value="5">Status</option>
                    <option value="6">Customer ID</option>
                </select>
                <input type="text" id="filterInput" placeholder="Search...">
            </div>

            <div class="recent-orders">
                <h2>All Transactions</h2>
                <table id="transactionTable">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Transaction Id</th>
                            <th>Transaction Amount</th>
                            <th>Transaction Details</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Customer Id</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'phps/db_connection.php';
                        try {
                            $query = "SELECT * FROM transactions ORDER BY transId DESC";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();
                            $srNo = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$srNo}</td>
                                        <td>{$row['TransId']}</td>
                                        <td>" . (isset($row['transAmount']) ? number_format((float)$row['transAmount'], 2) : '0.00') . "</td>
                                        <td>{$row['TransDetail']}</td>
                                        <td>{$row['TransDate']}</td>
                                        <td>{$row['TransStatus']}</td>
                                        <td>{$row['TransCustomerId']}</td>
                                        <td>";
                                if (!empty($row['invoicePath'])) {
                                    $invoiceFile = basename($row['invoicePath']);
                                    echo "<a href='phps/download_invoice.php?invoice=" . urlencode($invoiceFile) . "' target='_blank' class='download-btn'>Download</a>";
                                } else {
                                    echo "<span class='no-invoice'>No Invoice</span>";
                                }
                                echo "</td></tr>";
                                $srNo++;
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='8'>Error fetching inquiries: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- JavaScript -->
    <script>
        document.getElementById('filterInput').addEventListener('keyup', filterTable);
        document.getElementById('filterColumn').addEventListener('change', filterTable);

        function filterTable() {
            const input = document.getElementById('filterInput').value.toLowerCase();
            const column = document.getElementById('filterColumn').value;
            const rows = document.querySelectorAll('#transactionTable tbody tr');

            rows.forEach(row => {
                let shouldDisplay = false;

                if (column === 'all') {
                    // Search in all columns
                    row.querySelectorAll('td').forEach(td => {
                        if (td.textContent.toLowerCase().includes(input)) {
                            shouldDisplay = true;
                        }
                    });
                } else {
                    // Search in a specific column
                    const targetCell = row.querySelectorAll('td')[column];
                    if (targetCell && targetCell.textContent.toLowerCase().includes(input)) {
                        shouldDisplay = true;
                    }
                }

                row.style.display = shouldDisplay ? '' : 'none';
            });
        }
    </script>
</body>

</html>
