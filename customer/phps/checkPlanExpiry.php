<?php

include 'db_connection.php'; // Ensure this file correctly establishes a database connection

// Assuming the user ID is passed via session or a request
$userId = $_SESSION['id']; // You can also get user ID from the request if it's passed via GET or POST

// Fetch the active plan status for the user
$sql = "SELECT planExpiryDate FROM user_plans WHERE userId = :userId AND planStatus = 'active'";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // If an active plan is found, send back the expiry date
    echo json_encode([
        'status' => 'active',
        'expiryDate' => $result['planExpiryDate']
    ]);
} else {
    // If no active plan is found, send back a message indicating no active plan
    echo json_encode([
        'status' => 'noActivePlan',
        'message' => 'No active plan found. Please proceed with payment.'
    ]);
}

?>
