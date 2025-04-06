<?php
include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/phps/db_connection.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serverId = isset($_POST['serverId']) ? intval($_POST['serverId']) : 0;
    $serverName = $_POST['serverName'];
    $location = $_POST['location'];
    $capacity = floatval($_POST['capacity']);
    $usedCapacity = floatval($_POST['usedCapacity']);
    $status = $_POST['status'];

    try {
        if ($serverId > 0) {
            // Update existing server
            $query = "UPDATE server_resources SET 
                      serverName = ?, location = ?, capacity = ?, usedCapacity = ?, status = ? 
                      WHERE serverId = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$serverName, $location, $capacity, $usedCapacity, $status, $serverId]);
        } else {
            // Insert new server
            $query = "INSERT INTO server_resources (serverName, location, capacity, usedCapacity, status) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$serverName, $location, $capacity, $usedCapacity, $status]);
        }

        header("Location: http://localhost/ISPProject/admin/server_management.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>
