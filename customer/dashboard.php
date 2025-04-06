<?php
session_start();

// Enable error reporting for debugging (Remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logout logic
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /loginPage.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: /login.php"); // Redirect to login page if not logged in
    exit();
}

// Include database connection
include 'phps/db_connection.php';

// Fetch user information
$userId = $_SESSION['id'];

try {
    // Fetch user info
    $userQuery = "SELECT * FROM rgInfo WHERE cId = ?";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->execute([$userId]);
    $userInfo = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$userInfo) {
        $userInfoError = "User information not found. Please contact support.";
    }

    // Fetch account info
    $accountQuery = "SELECT * FROM accountinfo WHERE cId = ?";
    $accountStmt = $pdo->prepare($accountQuery);
    $accountStmt->execute([$userId]);
    $accountInfo = $accountStmt->fetch(PDO::FETCH_ASSOC);

    if (!$accountInfo) {
        $accountInfoError = "Account information not found. Please check your account settings.";
    }

    // Fetch plan info
    $planInfo = [];
    if ($accountInfo && isset($accountInfo['planId'])) {
        $planQuery = "SELECT * FROM plans WHERE planId = ?";
        $planStmt = $pdo->prepare($planQuery);
        $planStmt->execute([$accountInfo['planId']]);
        $planInfo = $planStmt->fetch(PDO::FETCH_ASSOC);
    }

    if (empty($planInfo)) {
        $planInfoError = "Plan information not found. Please select a valid plan.";
    }

    // Calculate plan expiry and remaining days
    $expiryDateInfo = [];
    if ($accountInfo && isset($accountInfo['planId'])) {
        $expiryDateQuery = "
            SELECT 
                DATE_ADD(planActivationDate, INTERVAL planValidity DAY) AS expiryDate,
                DATEDIFF(DATE_ADD(planActivationDate, INTERVAL planValidity DAY), NOW()) AS remainingDays
            FROM accountinfo 
            JOIN plans ON accountinfo.planId = plans.planId
            WHERE cId = ?";
        $expiryDateStmt = $pdo->prepare($expiryDateQuery);
        $expiryDateStmt->execute([$userId]);
        $expiryDateInfo = $expiryDateStmt->fetch(PDO::FETCH_ASSOC);
    }

    if (empty($expiryDateInfo)) {
        $expiryDateError = "Plan expiry information not available. Please check your plan details.";
    }

    // Example data for charts (replace with actual logic as needed)
    $dataUsage = 60; // Replace with actual data usage logic
    $remainingData = 40; // Replace with actual remaining data logic
    $monthlyData = [10, 15, 8, 12]; // Replace with actual monthly usage logic
    
} catch (PDOException $e) {
    $dbError = "Database error: " . $e->getMessage();
}
$profilePhoto = $userInfo['profilePhoto'] ?? 'default-avatar.png';
echo $profilePhoto;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Home</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">

        <!-- ==================================End of Aside=============================================== -->
        <main>
            <!-- Dashboard Header -->
            <h1>Dashboard Overview</h1>

            <!-- Top Section with User Info -->
            <div class="top">
            <img src="<?= htmlspecialchars($profilePhoto); ?>" alt="Profile Photo" class="profile-photo">
                <a href="profile.php">
                    <h3><?= htmlspecialchars($userInfo['cName']); ?></h3>
                </a>
            </div>

            <!-- Information Section -->
            <div class="infoSec">
                <!-- Account Info Section -->
                <div class="accInfo">
                    <h3>Account Information</h3>
                    <table>
                        <tbody>
                            <tr>
                                <td>Account Status:</td>
                                <td><b class="success"><?= htmlspecialchars($accountInfo['accStatus'] ?? 'N/A'); ?></b></td>
                            </tr>
                            <tr>
                                <td>Account Number:</td>
                                <td><?= htmlspecialchars($accountInfo['cId'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td>Open Service Requests:</td>
                                <td class="danger"><?= htmlspecialchars($accountInfo['openRequests'] ?? '0'); ?></td>
                            </tr>
                            <tr>
                                <td>Current IP:</td>
                                <td><?= htmlspecialchars($accountInfo['currentIP'] ?? 'N/A'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Plan Info Section -->
                <div class="planInfo">
                    <h3>Plan Usage Overview</h3>
                    <canvas id="pieChart"></canvas>
                    <table>
                        <tbody>
                            <tr>
                                <td>Plan:</td>
                                <td><?= htmlspecialchars($planInfo['planName'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td>Activation Date:</td>
                                <td><?= htmlspecialchars($accountInfo['planActivationDate'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td>Expiry Date:</td>
                                <td><?= htmlspecialchars($expiryDateInfo['expiryDate'] ?? 'N/A'); ?></td>
                            </tr>
                            <tr>
                                <td>Remaining Days:</td>
                                <td><?= htmlspecialchars($expiryDateInfo['remainingDays'] ?? 'N/A'); ?>/<?= htmlspecialchars($planInfo['planValidity'] ?? 'N/A'); ?> Days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Graph Section -->
            <div class="graphSection">
                <h3>Monthly Data Usage</h3>
                <canvas id="lineGraph"></canvas>
            </div>
        </main>

        <!-- =========================End of Main======================= -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie Chart for Plan Usage
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Used Data', 'Remaining Data'],
                datasets: [{
                    label: 'Data Usage',
                    data: [<?= $dataUsage; ?>, <?= $remainingData; ?>],
                    backgroundColor: ['#7380ec', '#dce1eb'],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });

        // Line Graph for Monthly Usage
        const ctxLine = document.getElementById('lineGraph').getContext('2d');
        const lineGraph = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Data Usage (GB)',
                    data: <?= json_encode($monthlyData); ?>,
                    borderColor: '#7380ec',
                    backgroundColor: 'rgba(115, 128, 236, 0.2)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        }
                    },
                    y: {
                        grid: {
                            color: '#f5f5f5',
                        },
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>
    <?php include 'css/dashboard.php'; ?>
</body>
</html>
