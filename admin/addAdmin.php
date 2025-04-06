<?php
include 'phps/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['final_submit'])) {
    $cName = $_POST['name'] ?? '';
    $cEmail = $_POST['email'] ?? '';
    $cContactNo = $_POST['contact'] ?? '';
    $cAddress = $_POST['address'] ?? '';
    $cPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $empRole = $_POST['empRole'] ?? '';
    $empSalary = $_POST['empSalary'] ?? '';
    $role = $_POST['role'] ?? 'Customer';

    if ($cPassword !== $confirmPassword) {
        die("Passwords do not match!");
    }

    // Handle profile image upload
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $profilePhoto = null;
    if (!empty($_FILES["profilephoto"]["name"])) {
        $fileName = time() . "_" . basename($_FILES["profilephoto"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($fileType, $allowedTypes)) {
            die("Error: Only JPG, PNG, and GIF files are allowed.");
        }

        if (move_uploaded_file($_FILES["profilephoto"]["tmp_name"], $targetFilePath)) {
            $profilePhoto = $targetFilePath;
        } else {
            die("Error: Failed to upload the profile picture.");
        }
    }

    $role = $_POST['role'] ?? 'Customer'; // Default to 'Customer' if not provided

    // Insert into rginfo table with the role as either 'Admin' or 'Staff'
    $sql = "INSERT INTO rginfo (cName, cEmail, cContactNo, cAddress, cPassword, Role, profilePhoto) 
        VALUES (:cName, :cEmail, :cContactNo, :cAddress, :cPassword, :role, :profilePhoto)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cName' => $cName,
        ':cEmail' => $cEmail,
        ':cContactNo' => $cContactNo,
        ':cAddress' => $cAddress,
        ':cPassword' => $cPassword,
        ':role' => $role, // This will save either 'Admin' or 'Staff'
        ':profilePhoto' => $profilePhoto
    ]);


    $cId = $pdo->lastInsertId();

    // Insert into employee table
    $sql = "INSERT INTO employee (cId, empStatus, empRole, empSalary, salaryToPay, dateJoined, lastPaidDate) 
            VALUES (:cId, 'working', :empRole, :empSalary, :salaryToPay, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cId' => $cId,
        ':empRole' => $empRole,
        ':empSalary' => $empSalary,
        ':salaryToPay' => $empSalary
    ]);

    echo "<script>alert('Registration Complete!'); window.location.href='cDashboard.php';</script>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="addAdmin.css">
    <script>
        function showNextForm() {
            document.getElementById("userForm").classList.add("hide-left");
            setTimeout(() => {
                document.getElementById("userForm").style.display = "none";
                document.getElementById("employeeForm").style.display = "block";
                document.getElementById("employeeForm").classList.add("show-right");
            }, 500);
        }

        function showPrevForm() {
            document.getElementById("employeeForm").classList.add("hide-right");
            setTimeout(() => {
                document.getElementById("employeeForm").style.display = "none";
                document.getElementById("userForm").style.display = "block";
                document.getElementById("userForm").classList.add("show-left");
            }, 500);
        }
    </script>
</head>

<body>
    <div class="blueCircle"></div>
    <div class="container">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="signup-section" id="userForm">
                <div class="upperText">
                    <h1>Create Your Account</h1>
                    <p>Fill out the details to sign up for our services.</p>
                </div>
                <div class="inpFileds">
                    <div class="form-group">
                        <input type="text" placeholder="Full Name" name="name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" placeholder="Email" name="email" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" placeholder="Contact Number" name="contact" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="Address" name="address" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Position:</label>
                        <select name="role" id="role" required>
                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="password" placeholder="PIN" name="password" maxlength="4" required>
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Confirm PIN" name="confirm_password" maxlength="4" required>
                    </div>
                    <div class="form-group">
                        <label>Upload Profile Photo:</label>
                        <input type="file" name="profilephoto" required>
                    </div>
                    <button type="button" class="btn" onclick="showNextForm()">Next</button>
                </div>
            </div>


            <div class="signup-section" id="employeeForm" style="display:none;">
                <div class="upperText">
                    <h1>Employee Details</h1>
                    <p>Provide your employee details.</p>
                </div>
                <div class="inpFileds">
                    <div class="form-group">
                        <input type="text" placeholder="Role" name="empRole" required>
                    </div>
                    <div class="form-group">
                        <input type="number" placeholder="Salary" name="empSalary" required>
                    </div>
                    <button type="button" class="btn" onclick="showPrevForm()">Back</button>
                    <button type="submit" class="btn" name="final_submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>