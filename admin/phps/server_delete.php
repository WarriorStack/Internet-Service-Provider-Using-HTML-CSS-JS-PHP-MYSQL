<?php
include 'db_connection.php'; // Ensure correct path

if (isset($_GET['id'])) {
    $serverId = intval($_GET['id']); // Sanitize input

    try {
        // Prepare and execute the delete query
        $stmt = $pdo->prepare("DELETE FROM server_resources WHERE serverId = ?");
        $stmt->execute([$serverId]);

        // Redirect back with a success message
        header("Location: ../server_management.php?delete=success");
        exit();
    } catch (PDOException $e) {
        echo "Error deleting server: " . $e->getMessage();
    }
} else {
    echo "Invalid request!";
}
?>
