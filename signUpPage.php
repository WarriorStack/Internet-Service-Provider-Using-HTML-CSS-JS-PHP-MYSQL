<?php

// Database connection details (Using PDO)
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "projectisp";

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function accountInfoInsert($cEmail, $pdo)
{
    $sql = "SELECT cId FROM rginfo WHERE cEmail = :cEmail";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['cEmail' => $cEmail]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $cId = $result['cId'];

        $insertSql = "INSERT INTO accountinfo (cId, planId, planActivationDate) 
                      VALUES (:cId, NULL, NOW())";
        $insertStmt = $pdo->prepare($insertSql);
        $insertStmt->execute(['cId' => $cId]);

        if ($insertStmt->rowCount() > 0) {
            echo "Account info added successfully!";
            header("Location: success.php");
            exit();
        } else {
            $errorInfo = $insertStmt->errorInfo();
            echo "Failed to add account info. Error: " . $errorInfo[2];
        }
    } else {
        echo "User not found!";
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $macId = $_POST['macId'];
    $cName = $_POST['name'];
    $cEmail = $_POST['email'];
    $cContactNo = $_POST['contact'];
    $cAddress = $_POST['address'];
    $cPassword = $_POST['password'];
    $cConfirmPassword = $_POST['confirm_password'];
    $cAns = $_POST['favorite_sport'];

    if ($cPassword !== $cConfirmPassword) {
        die("Passwords do not match!");
    }

    // Handle profile image upload
    $targetDir = "uploads/";

// Ensure the 'uploads' directory exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); // Create directory if not exists
}

    $profileImage = $_FILES["profile_photo"]["name"];
    $targetFilePath = $targetDir . basename($profileImage);
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allow only JPG, PNG, JPEG
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (!in_array($imageFileType, $allowedTypes)) {
        die("Only JPG, JPEG, and PNG files are allowed.");
    }

    if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $targetFilePath)) {
        echo "Profile photo uploaded successfully!";
    } else {
        die("Error uploading profile photo.");
    }

    // Insert into rginfo table
    $sql = "INSERT INTO rginfo (cMacAddress, cName, cEmail, cContactNo, cAddress, cPassword, cAns, ProfilePhoto) 
            VALUES (:macId, :cName, :cEmail, :cContactNo, :cAddress, :cPassword, :cAns, :ProfilePhoto)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':macId' => $macId,
            ':cName' => $cName,
            ':cEmail' => $cEmail,
            ':cContactNo' => $cContactNo,
            ':cAddress' => $cAddress,
            ':cPassword' => $cPassword,
            ':cAns' => $cAns,
            ':ProfilePhoto' => $targetFilePath
        ]);

        accountInfoInsert($cEmail, $pdo);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="signUpPage.css">
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profilePreview');
                output.src = reader.result;
                output.style.display = "block";
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>

<body>
    <div class="blueCircle"></div>
    <div class="container">
        <div class="signup-section">
            <div class="upperText">
                <h1>Create Your Account</h1>
                <p>Fill out the details to sign up for our services.</p>
            </div>
            <div class="inpFileds">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" placeholder="Mac Address" name="macId" maxlength="12" required>
                    </div>
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
                        <input type="password" placeholder="PIN" name="password" maxlength="4" required>
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="Confirm PIN" name="confirm_password" maxlength="4" required>
                    </div>
                    <div class="form-group">
                        <input type="text" placeholder="What Is Your Favorite Sport" name="favorite_sport" required>
                    </div>
                    <div class="form-group">
                        <label for="profile_photo">Upload Profile Photo:</label>
                        <input type="file" name="profile_photo" id="profile_photo" accept="image/*" onchange="previewImage(event)" required>
                        <img id="profilePreview" src="#" alt="Profile Preview" style="display: none; width: 100px; height: 100px; margin-top: 10px; border-radius: 50%;">
                    </div>
                    <button type="submit" class="btn">Sign Up</button>
                </form>
            </div>
        </div>
        <div class="info-section">
            <div class="icon">🔒</div>
            <h2>Secure Sign Up</h2>
            <p>Your data is encrypted and securely stored. We ensure the highest level of protection for your information.</p>
        </div>
    </div>
</body>

</html>
