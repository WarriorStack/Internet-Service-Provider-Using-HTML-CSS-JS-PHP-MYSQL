<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaintId = $_POST['complaintId'];
    $status = $_POST['status'];

    $query = "UPDATE complaints SET Status = :status WHERE Id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $complaintId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
