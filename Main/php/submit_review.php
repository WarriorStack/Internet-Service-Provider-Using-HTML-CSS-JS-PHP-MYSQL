<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "projectisp");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit();
}

// Get form data
$userId = $_POST['userId'] ?? '';
$userPassword = $_POST['userPassword'] ?? '';
$rating = $_POST['rating'] ?? '';
$reviewMessage = $_POST['reviewMessage'] ?? '';

if (empty($userId) || empty($userPassword) || empty($rating) || empty($reviewMessage)) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit();
}

// Check user credentials
$sql = "SELECT * FROM rginfo WHERE cId = ? AND cPassword = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $userId, $userPassword);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Check if review already exists
    $checkSql = "SELECT * FROM review WHERE user_id = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $existingReview = $stmt->get_result();

    if ($existingReview->num_rows > 0) {
        // Update existing review
        $update = "UPDATE review SET rating = ?, review_text = ? WHERE user_id = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("sss", $rating, $reviewMessage, $userId);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Review updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update review"]);
        }
    } else {
        // Insert new review
        $insert = "INSERT INTO review (user_id, rating, review_text) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("sss", $userId, $rating, $reviewMessage);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Review submitted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to insert review"]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}

$conn->close();
?>
