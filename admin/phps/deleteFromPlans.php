<?php
include 'db_connection.php'; // Include your database connection file

// Check if the planNameToDelete is set
if (isset($_POST['planNameToDelete'])) {
    $planNameToDelete = $_POST['planNameToDelete'];

    // Prepare SQL query to delete the plan
    $sql = "DELETE FROM plans WHERE planName = :planName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':planName', $planNameToDelete, PDO::PARAM_STR);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo "success"; // Successfully deleted the plan
    } else {
        echo "error"; // Failed to delete the plan
    }
} else {
    echo "error"; // planNameToDelete is not set
}
?>
