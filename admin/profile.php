<?php

// Suppress warning and ensure session is started properly
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/ISPProject/loginPage.php");
    exit();
}

include 'phps/db_connection.php';

// $cid = $_SESSION['id'];
$cid = $_SESSION['id'];

// Fetching customer registration information
$rgInfo = "select * from rgInfo where cId = ?";
$rgInfoStmt = $pdo->prepare($rgInfo);
$rgInfoStmt->execute([$cid]);
$rgInfoValue = $rgInfoStmt->fetch(PDO::FETCH_ASSOC);
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
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>
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
                        <h4>Answer :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['Role']); ?></p>
                        </h4>
                        <h4>Registration Date :- <p class="infoAns"><?= htmlspecialchars($rgInfoValue['rgDate']); ?></p>
                        </h4>
                    </div>
                </div>
            </div>
        </main>
    </div>
  </body>

</html>