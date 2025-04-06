<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="Assets/logo2.png" alt=".img">
                    <h2>Speed<span class="danger">NET</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-symbols-outlined">close</span>
                </div>
            </div>

            <div class="sidebar">
                <!-- Sidebar links with icons for navigation -->
                <a href="dashboard.html">
                    <span class="material-symbols-outlined">grid_view</span>
                    <h3>Dashboard</h3>
                </a>
                <a href="customers.html">
                    <span class="material-symbols-outlined">person_outline</span>
                    <h3>Customer</h3>
                </a>
                <a href="transactions.html">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <h3>Transactions</h3>
                </a>
                <a href="analytics.html">
                    <span class="material-symbols-outlined">insights</span>
                    <h3>Analytics</h3>
                </a>
                <a href="messages.html">
                    <span class="material-symbols-outlined">mail_outline</span>
                    <h3>Message</h3>
                </a>
                <a href="plans.html" class="active">
                    <span class="material-symbols-outlined">inventory</span>
                    <h3>Plans</h3>
                </a>
                <a href="complaints.html">
                    <span class="material-symbols-outlined">report_gmailerrorred</span>
                    <h3>Complaints</h3>
                </a>
                <a href="inquiry.html">
                    <span class="material-symbols-outlined">person_add</span>
                    <h3>Inquiry</h3>
                </a>
                <a href="settings.html">
                    <span class="material-symbols-outlined">settings</span>
                    <h3>Settings</h3>
                </a>
                <a href="change-plan.html">
                    <span class="material-symbols-outlined">add</span>
                    <h3>Change Plan</h3>
                </a>
                <a href="staff.html">
                    <span class="material-symbols-outlined">groups</span>
                    <h3>Staff</h3>
                </a>
                <a href="logout.html">
                    <span class="material-symbols-outlined">logout</span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>

        <main>
            <div class="plans">
                <div class="plan-card personalInfo">
                    <h2>-- Personal Information --</h2>
                    <div class="info">
                        <h4>Customer Id :- <p class="infoAns">dsfasfaf</p></h4>
                        <h4>Name :- <p class="infoAns"></p></h4>
                        <h4>Email :- <p class="infoAns"></p></h4>
                        <h4>Phone Number :- <p class="infoAns"></p></h4>
                        <h4>Password :- <p class="infoAns"></p></h4>
                        <h4>Address :- <p class="infoAns"></p></h4>
                        <h4>Answer  :- <p class="infoAns"></p></h4>
                        <h4>Registration Date  :- <p class="infoAns"></p></h4>
                    </div>
                </div>
                <div class="plan-card accountInfo">
                    <h2>-- Account Information --</h2>
                    <div class="info">
                        <h4>Customer Id :- <p class="infoAns"></p></h4>
                        <h4>Status :- <p class="infoAns"></p></h4>
                        <h4>Mac Address :- <p class="infoAns"></p></h4>
                        <h4>Currunt usage :- <p class="infoAns"></p></h4>
                        <h4>Status Ip :- <p class="infoAns"></p></h4>
                    </div>
                </div>

                <div class="plan-card curruntPlanInfo">
                    <h2>-- Plan Information --</h2>
                    <div class="info">
                        <h4>Name :- <p class="infoAns"></p></h4>
                        <h4>Activation Date :- <p class="infoAns"></p></h4>
                        <h4>Expiry Date :- <p class="infoAns"></p></h4>
                        <h4>Remaining Days :- <p class="infoAns"></p></h4>
                        <h4>Speed :- <p class="infoAns"></p></h4>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>