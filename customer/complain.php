<?php

session_start();

include('phps/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/ISPProject/loginPage.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch previous complaints from the database
try {
    $query = "SELECT * FROM complaints WHERE customerId = :user_id ORDER BY Date DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    // Fetch the complaints using PDO's fetchAll
    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Get all complaints for the user
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints</title>
    <link rel="stylesheet" href="complain.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">

        <main>
            <h1>Complaint Page</h1>
            <div class="complaint-section">
                <div class="complaint-form">
                    <h3>Submit a Complaint</h3>
                    <form action="phps/submit_complaint.php" method="POST">
                        <textarea name="complaint" rows="5" placeholder="Describe your issue..." required></textarea>
                        <button type="submit" class="submit-btn">Submit Complaint</button>
                    </form>
                </div>

                <div class="complaint-history">
                    <h3>Your Previous Complaints</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Complaint ID</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Display previous complaints
                            if (count($complaints) > 0) {
                                foreach ($complaints as $row) {
                                    echo "<tr>
                                        <td>" . $row['Id'] . "</td>
                                        <td>" . $row['Date'] . "</td>
                                        <td>" . $row['Status'] . "</td>
                                        <td>" . $row['Details'] . "</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No complaints found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

    </div>
    <div id="toast" class="toast"></div>
    <script>
        window.addEventListener('load', () => {
            document.querySelector('main').classList.add('loaded');
            document.querySelectorAll('.complaint-form, .complaint-history').forEach(el => {
                el.classList.add('loaded');
            });

            // Display Toast for success/error messages
            <?php if (isset($_SESSION['success'])): ?>
                showToast("<?php echo $_SESSION['success']; ?>", "success");
                <?php unset($_SESSION['success']); ?>
            <?php elseif (isset($_SESSION['error'])): ?>
                showToast("<?php echo $_SESSION['error']; ?>", "error");
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        });

        // Function to show toast
        function showToast(message, type) {
            var toast = document.getElementById("toast");
            toast.textContent = message;
            toast.className = "toast show " + type;

            // Automatically hide after 3 seconds
            setTimeout(function() {
                toast.className = toast.className.replace("show", "");
            }, 3000);
        }
    </script>
    <!-- <script src="/customer/js/dashboardWorker.js"></script> -->
    <?php include 'css/dashboard.php'; ?>
</body>
</html>