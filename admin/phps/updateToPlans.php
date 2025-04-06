<?php
include 'db_connection.php';

if (isset($_POST['planPrice'], $_POST['planSpeed'], $_POST['planValidity'], $_POST['oldPlanName'])) {
    $oldPlanName = htmlspecialchars($_POST['oldPlanName']);
    $planPrice = htmlspecialchars($_POST['planPrice']);
    $planSpeed = htmlspecialchars($_POST['planSpeed']);
    $planValidity = htmlspecialchars($_POST['planValidity']);

    // Update plan query
    $sql = "UPDATE plans SET planPrice = ?, planSpeed = ?, planValidity = ? WHERE planName = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$planPrice, $planSpeed, $planValidity, $oldPlanName])) {
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "error";
}

?>
