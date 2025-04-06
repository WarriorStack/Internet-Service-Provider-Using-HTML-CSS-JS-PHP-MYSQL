<?php
ob_start();
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projectisp";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize input to prevent SQL Injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check credentials and fetch user role
    $sql = "SELECT cId, Role FROM rginfo WHERE cEmail = '$email' AND cPassword = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $_SESSION['id'] = $row['cId'];  // Store user ID in session
        $_SESSION['role'] = $row['Role'];  // Store user role in session

        // Redirect based on role
        if ($row['Role'] == 'Admin') {
            header("Location: admin/cDashboard.php");
        } else {
            header("Location: customer/dashboard.php");
        }
        exit();
    } else {
        // Login failed message
        $loginMessage = "Login failed. Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="loginPage.css">
</head>

<body>
    <div class="blueCircle"></div>
    <div class="container">
        <!-- Left Section -->
        <div class="login-section">
            <h1>Welcome Back</h1>
            <p>Sign in to manage your internet services</p>
            <form action="loginPage.php" method="POST">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                    <label>Email</label>
                    <!-- <span class="icon">@</span> -->
                </div>
                <div class="form-group">
                <input type="password" name="password" id="password" placeholder="Password" maxlength="4" required>
                    <label>Password</label>
                    <!-- <span class="icon">👁️</span> -->
                </div>
                <div class="form-actions">
                    <label>
                        <input type="checkbox"> Remember me
                    </label>
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="btn">Sign In</button>
            </form>
            <p class="signup">Don't have an account? <a href="signUpPage.php">Sign up</a></p>

            <!-- Display login message (success or failure) -->
            <p class="login-message" style="color: red;"><?php echo $loginMessage; ?></p>
        </div>

        <!-- Right Section -->
        <div class="info-section">
            <div class="icon">🔒</div>
            <h2>Secure Login</h2>
            <p>Access your account with our secure login system. We protect your data with enterprise-grade encryption.</p>
        </div>
    </div>

    <script>
        const labels = document.querySelectorAll('.form-control label')

        labels.forEach(label => {
            label.innerHTML = label.innerText
                .split('')
                .map((letter, idx) => `<span style="transition-delay:${idx * 50}ms">${letter}</span>`)
                .join('')
        })
    </script>

</body>

</html>

<?php
// Close the connection
$conn->close();
?>