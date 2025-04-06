<?php
include 'db_connection.php';

if (isset($_GET['planName'])) {
    $planName = $_GET['planName'];

    $sql = "SELECT planPrice, planSpeed, planValidity FROM plans WHERE planName = :planName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':planName', $planName);
    $stmt->execute();

    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($plan) {
        echo json_encode($plan);
    } else {
        echo json_encode(null);
    }
}
