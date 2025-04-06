<?php
// Start the session at the top of the page
session_start();

// Check if the session is active and the user is logged in
if (!isset($_SESSION['id'])) {
    // If not logged in, redirect to login page or show an error
    header("Location: /login.php");
    exit();
}

// Include the database connection file
include 'phps/db_connection.php';

$cid = $_SESSION['id']; // This is the logged-in user's ID
$selectedSpan = 31; // This will be the value selected by the user (e.g., 31 days, etc.)

// Fetching customer registration information
$rgInfo = "SELECT * FROM rgInfo WHERE cId = ?";
$rgInfoStmt = $pdo->prepare($rgInfo);
$rgInfoStmt->execute([$cid]);
$rgInfoValue = $rgInfoStmt->fetch(PDO::FETCH_ASSOC);

// Default values in case no data is found
if (!$rgInfoValue) {
    $rgInfoValue = [
        'cId' => 'N/A',
        'cName' => 'N/A',
        'cEmail' => 'N/A',
        'cContactNo' => 'N/A',
        'cPassword' => 'N/A',
        'cAddress' => 'N/A',
        'cAns' => 'N/A',
        'rgDate' => 'N/A'
    ];
}

// Calculating total usage within the selected time span
$totalUsagesInfo = "
    SELECT SUM(DataUsed) AS totalUsage
    FROM datausage
    WHERE cid = ? 
      AND TimeStamp >= (SELECT MAX(TimeStamp) - INTERVAL ? DAY
                       FROM datausage
                       WHERE cid = ?);
";
$totalUsagesInfoStmt = $pdo->prepare($totalUsagesInfo);
$totalUsagesInfoStmt->execute([$cid, $selectedSpan, $cid]);
$totalUsagesInfoValue = $totalUsagesInfoStmt->fetch(PDO::FETCH_ASSOC);

// Default values for total usage if no data is found
if (!$totalUsagesInfoValue) {
    $totalUsagesInfoValue = ['totalUsage' => 0];
}

// Fetching account information before the selected time span
$accInfo = "SELECT * FROM accountinfo WHERE cid = ?";
$accInfoStmt = $pdo->prepare($accInfo);
$accInfoStmt->execute([$cid]);
$accInfoValue = $accInfoStmt->fetch(PDO::FETCH_ASSOC);

// Default values for account info if no data is found
if (!$accInfoValue) {
    $accInfoValue = [
        'cId' => $cid,
        'accStatus' => 'N/A',
        'planActivationDate' => 'N/A',
        'planId' => null
    ];
}

// Fetching plan information before the selected time span
$planInfo = "SELECT * FROM plans WHERE planId = ?";
$planInfoStmt = $pdo->prepare($planInfo);
$planInfoStmt->execute([$accInfoValue['planId']]);
$planInfoValue = $planInfoStmt->fetch(PDO::FETCH_ASSOC);

// Default values for plan info if no data is found
if (!$planInfoValue) {
    $planInfoValue = [
        'planName' => 'N/A',
        'planSpeed' => 'N/A',
        'planValidity' => 'N/A'
    ];
}

// Calculating expiry date and remaining days of the plan 
$planExpiryInfo = "SELECT 
    a.planActivationDate, 
    p.planValidity, 
    DATE_ADD(a.planActivationDate, INTERVAL p.planValidity DAY) AS planExpiryDate,
    DATEDIFF(DATE_ADD(a.planActivationDate, INTERVAL p.planValidity DAY), NOW()) AS remainingDays
FROM 
    accountInfo a
JOIN 
    plans p 
ON 
    a.planId = ?;";
$planExpiryInfoStmt = $pdo->prepare($planExpiryInfo);
$planExpiryInfoStmt->execute([$accInfoValue["planId"]]);
$planExpiryInfoValue = $planExpiryInfoStmt->fetch(PDO::FETCH_ASSOC);

// Default values for plan expiry info if no data is found
if (!$planExpiryInfoValue) {
    $planExpiryInfoValue = [
        'planExpiryDate' => 'N/A',
        'remainingDays' => 'N/A'
    ];
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">

        <!-- ===========================================================Aside Ends ================================================================= -->

        <main>
            <div class="plans">
                <div class="plan-card personalInfo">
                    <h2>-- Personal Information --</h2>
                    <div class="info">
                        <h4>Customer Id :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['cId']); ?></p>
                        </h4>
                        <h4>Name :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['cName']); ?></p>
                        </h4>
                        <h4>Email :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['cEmail']); ?></p>
                        </h4>
                        <h4>Phone Number :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['cContactNo']); ?></p>
                        </h4>
                        <h4>Password :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['cPassword']); ?></p>
                        </h4>
                        <h4>Address :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['cAddress']); ?></p>
                        </h4>
                        <h4>Answer :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['cAns']); ?></p>
                        </h4>
                        <h4>Registration Date :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['rgDate']); ?></p>
                        </h4>
                    </div>
                </div>
                <div class="plan-card accountInfo">
                    <h2>-- Account Information --</h2>
                    <div class="info">
                        <h4>Customer Id :- <p class="infoAns"><?= htmlspecialchars($accInfoValue['cId']); ?></p>
                        </h4>
                        <h4>Status :- <p class="infoAns"><?= htmlspecialchars($accInfoValue['accStatus']); ?></p>
                        </h4>
                        <h4>Mac Address :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['cMacAddress']); ?></p>
                        </h4>
                        <h4>Last 31days usage :- <p class="infoAns"><?= htmlspecialchars($totalUsagesInfoValue['totalUsage']); ?></p>
                        </h4>
                        <h4>Status Ip :- <p class="infoAns">Default</p>
                        </h4>
                    </div>
                </div>

                <div class="plan-card curruntPlanInfo">
                    <h2>-- Plan Information --</h2>
                    <div class="info">
                        <h4>Name :- <p class="infoAns"><?= htmlspecialchars($planInfoValue['planName']) ?></p>
                        </h4>
                        <h4>Activation Date :- <p class="infoAns"><?= htmlspecialchars($accInfoValue['planActivationDate']) ?></p>
                        </h4>
                        <h4>Expiry Date :- <p class="infoAns"><?= htmlspecialchars($planExpiryInfoValue['planExpiryDate']) ?></p>
                        </h4>
                        <h4>Remaining Days :- <p class="infoAns"><?= htmlspecialchars($planExpiryInfoValue['remainingDays'])?></p>
                        </h4>
                        <h4>Speed :- <p class="infoAns"><?= htmlspecialchars($planInfoValue['planSpeed'])?></p>
                        </h4>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- <script src="js/dashboardWorker.js"></script> -->
    <?php include 'css/dashboard.php'; ?>
</body>
</html>