<?php
include 'phps/db_connection.php';
session_start();

if (!isset($_SESSION['cId'])) {
    die("Unauthorized access!");
}

$cId = $_SESSION['cId'];
$cEmail = $_SESSION['cEmail'];
$cPassword = $_SESSION['cPassword'];

// Verify email & password before inserting into employee table
$verifySql = "SELECT id FROM rginfo WHERE cEmail = :cEmail AND cPassword = :cPassword";
$verifyStmt = $pdo->prepare($verifySql);
$verifyStmt->execute([':cEmail' => $cEmail, ':cPassword' => $cPassword]);
$rginfo = $verifyStmt->fetch(PDO::FETCH_ASSOC);

if (!$rginfo) {
    die("User authentication failed!");
}

// If verification is successful, insert into employee table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['final_submit'])) {
    $empRole = $_POST['empRole'];
    $empSalary = $_POST['empSalary'];

    $sql = "INSERT INTO employee (cId, empStatus, empRole, empSalary, salaryToPay, dateJoined, lastPaidDate) 
            VALUES (:cId, 'working', :empRole, :empSalary, :salaryToPay, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cId' => $cId,
        ':empRole' => $empRole,
        ':empSalary' => $empSalary,
        ':salaryToPay' => $empSalary // Initial salary due = full salary
    ]);

    echo "Employee data added successfully!";
    session_destroy(); // Clear session after successful insertion
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Registration</title>
</head>
<body>
    <h2>Complete Employee Details</h2>
    <form action="" method="POST">
        <label for="empRole">Role:</label>
        <input type="text" name="empRole" required><br>

        <label for="empSalary">Salary:</label>
        <input type="number" name="empSalary" required><br>

        <button type="submit" name="final_submit">Submit</button>
    </form>
</body>
</html>
