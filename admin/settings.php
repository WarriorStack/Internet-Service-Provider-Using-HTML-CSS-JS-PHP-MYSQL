<?php
session_start();

// if (!isset($_SESSION['id'])) {
//     header("Location: /login.php");
//     exit();
// }

include 'phps/db_connection.php';

// $cid = $_SESSION['id'];
$cid = $_SESSION['id'];

$successMessage = ''; // Initialize successMessage variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['updateInfo'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $contactNo = $_POST['contactNo'];
        $address = $_POST['address'];

        $updateInfo = "UPDATE rgInfo SET cName = ?, cEmail = ?, cContactNo = ?, cAddress = ? WHERE cId = ?";
        $stmt = $pdo->prepare($updateInfo);
        $stmt->execute([$name, $email, $contactNo, $address, $cid]);

        $successMessage = 'Personal information updated successfully!';
    }

    if (isset($_POST['updatePassword'])) {
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updatePassword = "UPDATE rgInfo SET cPassword = ? WHERE cId = ?";
        $stmt = $pdo->prepare($updatePassword);
        $stmt->execute([$newPassword, $cid]);

        $successMessage = 'Password updated successfully!';
    }

    if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['profilePhoto']['name']);
        $targetFile = $targetDir . $fileName;
        move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $targetFile);

        $updatePhoto = "UPDATE rgInfo SET profilePhoto = ? WHERE cId = ?";
        $stmt = $pdo->prepare($updatePhoto);
        $stmt->execute([$targetFile, $cid]);

        $successMessage = 'Profile photo updated successfully!';
    }
}

$rgInfo = "SELECT * FROM rgInfo WHERE cId = ?";
$stmt = $pdo->prepare($rgInfo);
$stmt->execute([$cid]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

$profilePhoto = $userInfo['profilePhoto'] ?? 'default-avatar.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="settings.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>
        <main>
            <!-- Profile Photo Section -->
            <div class="plans">
                <div class="plan-card">
                    <div class="profile-photo-section">
                        <img src="<?= htmlspecialchars($profilePhoto); ?>" alt="Profile Photo" class="profile-photo">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="file" name="profilePhoto" accept="image/*" required>
                            <button type="submit">Update Photo</button>
                        </form>
                    </div>
                </div>

                <!-- Update Personal Information Section -->
                <div class="plan-card">
                    <h2>-- Update Personal Information --</h2>
                    <button id="editBtn">Edit</button>
                    <form method="POST" id="infoForm">
                        <label>Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($userInfo['cName']); ?>" readonly required>

                        <label>Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($userInfo['cEmail']); ?>" readonly required>

                        <label>Contact No</label>
                        <input type="text" name="contactNo" value="<?= htmlspecialchars($userInfo['cContactNo']); ?>" readonly required>

                        <label>Address</label>
                        <input type="text" name="address" value="<?= htmlspecialchars($userInfo['cAddress']); ?>" readonly required>

                        <button type="submit" name="updateInfo">Update Information</button>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="plan-card">
                    <h2>-- Change Password --</h2>
                    <form method="POST">
                        <label>New Password</label>
                        <input type="password" name="password" required>
                        <button type="submit" name="updatePassword">Update Password</button>
                    </form>
                </div>
            </div>

            <!-- Success Notification -->
            <?php if ($successMessage): ?>
            <div id="successNotification" class="success-notification">
                <p id="successMessage"><?= htmlspecialchars($successMessage); ?></p>
            </div>
            <?php endif; ?>
        </main>
    </div>
    <?php include 'css/dashboard.php'; ?>
    <script>
        // Ensure the script runs only after DOM content is loaded
        document.addEventListener('DOMContentLoaded', () => {
            const editBtn = document.getElementById('editBtn');
            const infoForm = document.getElementById('infoForm');
            const inputs = infoForm.querySelectorAll('input');
            const updateBtn = infoForm.querySelector('button[name="updateInfo"]');

            // Ensure editBtn exists
            if (editBtn) {
                editBtn.addEventListener('click', (event) => {
                    event.preventDefault(); // Prevent form submission on button click
                    const isReadOnly = inputs[0].hasAttribute('readonly');

                    // Disable all inputs when Edit is clicked
                    inputs.forEach(input => input.toggleAttribute('readonly', !isReadOnly));

                    // Ensure update button is always enabled
                    updateBtn.disabled = false;

                    // Toggle button text between Edit/Cancel
                    editBtn.textContent = isReadOnly ? 'Cancel' : 'Edit';
                });
            }
        });

        // Show success notification on form update
        <?php if ($successMessage): ?>
            window.onload = function() {
                const successNotification = document.getElementById('successNotification');
                successNotification.classList.add('show');
                setTimeout(() => {
                    successNotification.classList.remove('show');
                }, 4000); // Hide notification after 4 seconds
            }
        <?php endif; ?>
    </script>
</body>
</html>
