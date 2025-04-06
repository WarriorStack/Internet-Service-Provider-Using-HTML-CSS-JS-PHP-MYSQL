<?php


// Suppress warning and ensure session is started properly
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}


// Uncomment this when session validation is needed
if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/ISPProject/loginPage.php");
    exit();
}

// Get the current active page name
$activePage = basename($_SERVER['PHP_SELF']);

// Function to add 'active' class
function isActive($page)
{
    global $activePage;
    return ($activePage === basename($page)) ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/ISPProject/admin/css/dashboard.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>

    <aside>
        <div class="top">
            <div class="logo">
            <img src="/ISPProject/Assets/SpeedNet2.png" alt="Logo">
                <h2>Speed<span class="danger">NET</span></h2>
            </div>
        </div>

        <div class="sidebar">
            <a href="http://localhost/ISPProject/admin/cDashboard.php" class="<?= isActive('http://localhost/ISPProject/admin/cDashboard.php') ?>">
                <span class="material-symbols-outlined">grid_view</span>
                <h3>Dashboard</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/profile.php" class="<?= isActive('http://localhost/ISPProject/admin/profile.php') ?>">
                <span class="material-symbols-outlined">account_circle</span>
                <h3>Profile</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/customer.php" class="<?= isActive('http://localhost/ISPProject/admin/customer.php') ?>">
                <span class="material-symbols-outlined">person_outline</span>
                <h3>Customer</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/transactions.php" class="<?= isActive('http://localhost/ISPProject/admin/transactions.php') ?>">
                <span class="material-symbols-outlined">receipt_long</span>
                <h3>Transactions</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/server_management.php" class="<?= isActive('http://localhost/ISPProject/admin/server_management.php') ?>">
                <span class="material-symbols-outlined">host</span>
                <h3>Resources and Expences</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/plan.php" class="<?= isActive('http://localhost/ISPProject/admin/plan.php') ?>">
                <span class="material-symbols-outlined">settings_applications</span>
                <h3>Plan</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/complaints.php" class="<?= isActive('http://localhost/ISPProject/admin/complaints.php') ?>">
                <span class="material-symbols-outlined">report</span>
                <h3>Complaints</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/inquiry.php" class="<?= isActive('http://localhost/ISPProject/admin/inquiry.php') ?>">
                <span class="material-symbols-outlined">zoom_in</span>
                <h3>Inquiry</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/Reports/reports.php" class="<?= isActive('/ISPProject/admin/Reports/reports.php') ?>">
                <span class="material-symbols-outlined">lab_profile</span>
                <h3>Reports</h3>
            </a>

            <a href="http://localhost/ISPProject/admin/settings.php" class="<?= isActive('http://localhost/ISPProject/admin/settings.php') ?>">
                <span class="material-symbols-outlined">settings</span>
                <h3>Settings</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/updatePlan.php" class="<?= isActive('http://localhost/ISPProject/admin/updatePlan.php') ?>">
                <span class="material-symbols-outlined">upgrade</span>
                <h3>Update Plan</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/employeeDetails.php" class="<?= isActive('http://localhost/ISPProject/admin/employeeDetails.php') ?>">
                <span class="material-symbols-outlined">help_clinic</span>
                <h3>Staff Details</h3>
            </a>
            <a href="http://localhost/ISPProject/admin/addAdmin.php" class="<?= isActive('http://localhost/ISPProject/admin/addAdmin.php') ?>">
                <span class="material-symbols-outlined">engineering</span>
                <h3>Add Employee</h3>
            </a>
            <a href="#" id="theme-toggle-link">
                <span class="material-symbols-outlined">dark_mode</span>
                <h3>Toggle Theme</h3>
            </a>
            <a href="http://localhost/ISPProject/loginPage.php" id="logout-link">
                <span class="material-symbols-outlined">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
    </aside>

    <script>
        // Theme Toggle
        const themeToggleLink = document.getElementById('theme-toggle-link');
        const body = document.body;

        const currentTheme = localStorage.getItem('theme');
        if (currentTheme) {
            body.classList.add(currentTheme);
        } else {
            body.classList.add('light'); // Default theme
        }

        themeToggleLink.addEventListener('click', function(event) {
            event.preventDefault();
            if (body.classList.contains('light')) {
                body.classList.remove('light');
                body.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                body.classList.remove('dark');
                body.classList.add('light');
                localStorage.setItem('theme', 'light');
            }
        });

        // Logout Confirmation
        const logoutLink = document.getElementById('logout-link');
        logoutLink.addEventListener('click', function(event) {
            event.preventDefault();
            const userConfirmed = confirm("Are you sure you want to logout?");
            if (userConfirmed) {
                window.location.href = logoutLink.href;
            }
        });
    </script>

</body>

</html>