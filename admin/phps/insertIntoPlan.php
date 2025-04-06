<?php
include 'db_connection.php'; // Include your database connection

if (isset($_POST['planName'], $_POST['planPrice'], $_POST['planSpeed'], $_POST['planValidity'])) {
    $planName = htmlspecialchars($_POST['planName']);
    $planPrice = htmlspecialchars($_POST['planPrice']);
    $planSpeed = htmlspecialchars($_POST['planSpeed']) . " Mbps";
    $planValidity = htmlspecialchars($_POST['planValidity']);

    // Check if the plan already exists
    $checker = "SELECT * FROM plans WHERE planName = ?";
    $checkerStmt = $pdo->prepare($checker);
    $checkerStmt->execute([$planName]);
    
    if ($checkerStmt->rowCount() > 0) {
        echo "Name already exists";
        exit(); // Exit the script if the plan already exists
    }

    // Insert new plan
    $sql = "INSERT INTO plans (planName, planSpeed, planValidity, planPrice) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$planName, $planSpeed, $planValidity, $planPrice])) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}

?>
