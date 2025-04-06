<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$host = 'localhost';
$db = 'projectisp'; // Ensure this is the correct database name
$user = 'root'; // Update if using a different user
$pass = ''; // Leave empty if no password is set for root

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Correct query based on table schema
$sql = "SELECT r.rating, r.review_text AS message, r.created_at AS reviewDate, 
               rg.cName AS name, rg.profilePhoto AS profile_pic 
        FROM review r
        JOIN rginfo rg ON r.user_id = rg.cId
        ORDER BY r.created_at DESC";

$result = $conn->query($sql);

if (!$result) {
    die(json_encode(['success' => false, 'message' => 'Query failed: ' . $conn->error]));
}

$reviews = [];

while ($row = $result->fetch_assoc()) {
    $reviews[] = [
        'name' => $row['name'],
        'profile_pic' => $row['profile_pic'], 
        'rating' => (int)$row['rating'],
        'message' => $row['message']
    ];
}
// If no reviews are found
if (empty($reviews)) {
    die(json_encode(['success' => false, 'message' => 'No reviews found']));
}

echo json_encode(['success' => true, 'reviews' => $reviews]);

$conn->close();
