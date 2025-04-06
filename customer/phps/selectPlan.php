<?php
// Include the database connection file
include 'db_connection.php';

// Start the session to use session variables
session_start();

// Check if the form is submitted and the planId is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['planId'])) {
    $planId = $_POST['planId'];  // Get the selected plan ID
    $cId = $_SESSION['id'];  // Get the user ID from session

    // Check if session id is set
    if (!isset($cId)) {
        echo "Session ID is not set.";
        exit();  // Stop execution if session id is not found
    }

    // Check if the planId is valid (optional)
    if (empty($planId)) {
        echo "Please select a valid plan.";
        exit();
    }

    // Prepare the update SQL query
    $updateSql = "UPDATE accountinfo 
                  SET planId = :planId, planActivationDate = NOW() 
                  WHERE cId = :cId";

    try {
        // Prepare and execute the query
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute(['planId' => $planId, 'cId' => $cId]);

        // Debug: Check if the update was successful
        if ($updateStmt->rowCount() > 0) {
            echo "Plan updated successfully!";
        } else {
            echo "Failed to update plan or no change was made.";
        }

    } catch (PDOException $e) {
        // Catch and display any database errors
        echo "Error: " . $e->getMessage();
    }

} else {
    echo "Invalid request. No planId provided.";
}
?>
