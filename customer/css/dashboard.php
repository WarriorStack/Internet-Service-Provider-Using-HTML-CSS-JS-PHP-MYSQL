<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: http://localhost/ISPProject/loginPage.php");
    exit();
}

// Get the current active page from the session
$activePage = basename($_SERVER['PHP_SELF']);
?>
<aside>
    <div class="top">
        <div class="logo">
            <!-- <img src="https://fastly.picsum.photos/id/96/536/354.jpg?hmac=BkHTCZXyYAIIlUP9rQCjPGZ_maJq-EG9xMd0W5kr9z0"> -->
            <img src="../Assets/SpeedNet2.png">
            <h2>Speed<span class="danger">NET</span></h2>
        </div>
    </div>

    <div class="sidebar">
        <a href="dashboard.php" class="<?= $activePage === 'dashboard.php' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">grid_view</span>
            <h3>Dashboard</h3>
        </a>
        <a href="profile.php" class="<?= $activePage === 'profile.php' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">person_outline</span>
            <h3>Profile</h3>
        </a>
        <a href="transaction.php" class="<?= $activePage === 'transaction.php' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">receipt_long</span>
            <h3>Transactions</h3>
        </a>
        <a href="plan.php" class="<?= $activePage === 'plan.php' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">inventory</span>
            <h3>Plans</h3>
        </a>
        <a href="complain.php" class="<?= $activePage === 'complain.php' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">report_gmailerrorred</span>
            <h3>Complaints</h3>
        </a>
        <a href="settings.php" class="<?= $activePage === 'settings.php' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">settings</span>
            <h3>Settings</h3>
        </a>
        <a href="updatePlan.php" class="<?= $activePage === 'updatePlan.php' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">add</span>
            <h3>Change Plan / Recharge</h3>
        </a>

        <!-- Theme Toggle Link (similar to logout) -->
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
    // Toggle Theme function
    const themeToggleLink = document.getElementById('theme-toggle-link');
    const body = document.body;

    // Check the current theme in localStorage
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme) {
        body.classList.add(currentTheme);
    } else {
        body.classList.add('light'); // Default theme
    }

    themeToggleLink.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
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

    // Add confirmation dialog for the logout link
    const logoutLink = document.getElementById('logout-link');
    logoutLink.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default link behavior
        const userConfirmed = confirm("Are you sure you want to logout?");
        if (userConfirmed) {
            // If the user confirms, navigate to the logout URL
            window.location.href = logoutLink.href;
        }
    });
</script>