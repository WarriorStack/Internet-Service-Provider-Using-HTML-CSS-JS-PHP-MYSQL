<?php
header('Content-Type: application/json');

include 'connector.php';

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$personName = $_POST['personName'] ?? '';
$phoneNo = $_POST['phoneNo'] ?? '';
$email = $_POST['email'] ?? '';
$address = $_POST['address'] ?? '';

// Check if all fields are filled
if (!$personName || !$phoneNo || !$email || !$address) {
    echo json_encode(["success" => false, "error" => "All fields are required"]);
    exit();
}

// Insert into the database
$stmt = $conn->prepare("INSERT INTO enquiry (personName, phoneNo, email, address) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $personName, $phoneNo, $email, $address);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Database error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
