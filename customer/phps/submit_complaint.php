<?php
session_start();
include('db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: /login.php");
    exit();
}

// Handle complaint submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint'])) {
    $user_id = $_SESSION['id'];  // Get user ID from session
    $complaint_text = $_POST['complaint'];  // Get complaint text from form
    $date_submitted = date("Y-m-d H:i:s");

    // Prepare and execute the query to insert the complaint
    try {
        $query = "INSERT INTO complaints (CustomerId, Details) 
                  VALUES (:Id, :Details)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Id', $user_id);
        $stmt->bindParam(':Details', $complaint_text);
        
        $stmt->execute();  // Execute the query

        $_SESSION['success'] = "Complaint submitted successfully!";
        header("Location: http://localhost/ISPProject/customer/complain.php"); // Redirect back to complaints page after successful submission
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "There was an error submitting your complaint.";
        echo "Error: " . $e->getMessage();
    }
}
?>
