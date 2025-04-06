<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/phps/db_connection.php';

try {
    // Fetch total active customers
    $customerQuery = "SELECT COUNT(*) AS total_customers FROM rginfo WHERE role = 'customer'";
    $stmt = $pdo->prepare($customerQuery);
    $stmt->execute();
    $customerData = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalCustomers = $customerData['total_customers'];

    // Fetch total working employees
    $employeeQuery = "SELECT COUNT(*) AS total_employees FROM rginfo WHERE role IN ('admin', 'staff')";
    $stmt = $pdo->prepare($employeeQuery);
    $stmt->execute();
    $employeeData = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalEmployees = $employeeData['total_employees'];

    // Fetch total sales (sum of transAmount where transStatus = 'success')
    $salesQuery = "SELECT SUM(transAmount) AS total_sales FROM transactions WHERE transStatus = 'success'";
    $stmt = $pdo->prepare($salesQuery);
    $stmt->execute();
    $salesData = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalSales = $salesData['total_sales'] ?? 0;

    // Fetch total expenses (sum of employee salaries)
    $expenseQuery = "SELECT SUM(empsalary) AS total_expenses FROM employee";
    $stmt = $pdo->prepare($expenseQuery);
    $stmt->execute();
    $expenseData = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalExpenses = $expenseData['total_expenses'] ?? 0;

    // Fetch total active servers
    $serverQuery = "SELECT COUNT(*) AS total_active_servers FROM server_resources WHERE status = 'active'";
    $stmt = $pdo->prepare($serverQuery);
    $stmt->execute();
    $serverData = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalActiveServers = $serverData['total_active_servers'];

    // Calculate total income
    $totalIncome = $totalSales - $totalExpenses;
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/phps/db_connection.php';

try {
    // Fetch today's successful transactions
    $todayQuery = "SELECT t.transId, r.cName, t.transAmount, t.transDate, t.transDate
                   FROM transactions t
                   JOIN rginfo r ON t.transCustomerId = r.cid
                   WHERE t.transStatus = 'success' AND DATE(t.transDate) = CURDATE()
                   ORDER BY t.transDate DESC";

    $stmt = $pdo->prepare($todayQuery);
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Home</title>
    <link rel="stylesheet" href="cDashboard.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">

<body>
    <div class="container">

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>

        <!-- ==================================End of Aside=============================================== -->
        <main>
            <h1>Dashboard</h1>

            <div class="date">
                <input type="date">
            </div>

            <div class="insights">



                <!-- Total Sales Card -->
                <div class="sales">
                    <span class="material-symbols-outlined">analytics</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Total Sales</h3>
                            <h1>$<?php echo number_format($totalSales, 2); ?></h1>
                            <small class="text-muted">From Successful Transactions</small>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="number">
                                <p><?php echo ($totalSales > 0) ? '100%' : '0%'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =========================End of Sales======================= -->

                <!-- Total Expenses Card -->
                <div class="expenses">
                    <span class="material-symbols-outlined">bar_chart</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Total Expenses</h3>
                            <h1>$<?php echo number_format($totalExpenses, 2); ?></h1>
                            <small class="text-muted">Total Employee Salaries</small>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="number">
                                <p><?php echo ($totalExpenses > 0) ? '100%' : '0%'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =========================End of Expences======================= -->
                <!-- Total Income Card -->
                <div class="income">
                    <span class="material-symbols-outlined">stacked_line_chart</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Total Income</h3>
                            <h1>$<?php echo number_format($totalIncome, 2); ?></h1>
                            <small class="text-muted">From Successful Transactions</small>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="number">
                                <p><?php echo ($totalIncome > 0) ? '100%' : '0%'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- =========================End of Income======================= -->

                <div class="totalActiveCustomer">
                    <span class="material-symbols-outlined">stacked_line_chart</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Total Active Customers</h3>
                            <h1><?php echo htmlspecialchars($totalCustomers); ?></h1>
                            <small class="text-muted">All Time</small>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="number"></div>
                        </div>
                    </div>
                </div>
                <!-- =========================End of totalActiveCustomer======================= -->


                <!-- Total Employees -->
                <div class="totalActiveCustomer">
                    <span class="material-symbols-outlined">stacked_line_chart</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Total Working Employees</h3>
                            <h1><?php echo htmlspecialchars($totalEmployees); ?></h1>
                            <small class="text-muted">All Time</small>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="number"></div>
                        </div>
                    </div>
                </div>
                <!-- =========================End of totalEmployees======================= -->

                <div class="active-servers">
                    <span class="material-symbols-outlined">dns</span>
                    <div class="middle">
                        <div class="left">
                            <h3>Total Active Servers</h3>
                            <h1><?php echo htmlspecialchars($totalActiveServers); ?></h1>
                            <small class="text-muted">Currently Running</small>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="number"></div>
                        </div>
                    </div>
                </div>
                <!-- ========================= End of Active Servers Card ======================== -->

            </div>
            <!-- =========================End of Insights======================= -->
            <div class="recent-orders">
                <h2>Today's Payments</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Person Name</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Time</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($transactions)): ?>
                            <?php foreach ($transactions as $index => $transaction): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($transaction['cName']); ?></td>
                                    <td class="warning">Recharge</td>
                                    <td>$<?php echo number_format($transaction['transAmount'], 2); ?></td>
                                    <td><?php echo date("h:i A", strtotime($transaction['transDate'])); ?></td>
                                    <td class="primary"><a href="transaction_details.php?id=<?php echo $transaction['transId']; ?>">Details</a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No transactions today.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <a href="all_transactions.php">Show all</a>
            </div>
            <!-- ========================= End of Recent Recharges ======================== -->
</body>

<script>
    // Toggle Theme function
    const themeToggleLink = document.getElementById('theme-toggle-link');
    const body = document.body;

    // Check the current theme in localStorage
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        body.classList.add(currentTheme);
    } else {
        body.classList.add('light'); // Default theme
    }

    themeToggleLink.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        if (body.classList.contains('light')) {
            body.classList.remove('light');
            body.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        } else {
            body.classList.remove('dark');
            body.classList.add('light');
            localStorage.setItem('theme', 'light');
        }
    });

    // Add confirmation dialog for the logout link
    const logoutLink = document.getElementById('logout-link');
    logoutLink.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        const userConfirmed = confirm("Are you sure you want to logout?");
        if (userConfirmed) {
            // If the user confirms, navigate to the logout URL
            window.location.href = logoutLink.href;
        }
    });
</script>

</html>