<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP Portal Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            background-color: #1e1e1e;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar h2 {
            color: #4caf50;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #ffffff;
            font-size: 16px;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #4caf50;
            color: #121212;
        }

        .sidebar .profile {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .sidebar .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .sidebar .profile .info {
            font-size: 14px;
        }

        .sidebar .profile .info p {
            margin-bottom: 5px;
        }

        .dashboard {
            flex: 1;
            padding: 20px;
        }

        .dashboard .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .dashboard .header h1 {
            font-size: 24px;
        }

        .dashboard .header .user {
            display: flex;
            align-items: center;
        }

        .dashboard .header .user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #1e1e1e;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.4);
        }

        .card h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .card .tag {
            display: inline-block;
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 20px;
            background-color: #4caf50;
            color: #121212;
            margin-bottom: 10px;
        }

        .card .highlight {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .card a {
            color: #4caf50;
            text-decoration: none;
            font-size: 14px;
        }

        .data-usage {
            margin-top: 20px;
        }

        .data-usage h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .data-usage .chart {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            height: 150px;
            background-color: #1e1e1e;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .data-usage .chart .bar {
            width: 30px;
            background-color: #4caf50;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .data-usage .chart .bar:hover {
            background-color: #388e3c;
        }

        .data-usage .chart .bar span {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            text-align: center;
            color: #ffffff;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>ISP Portal</h2>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Broadband</a></li>
            <li><a href="#">Usage</a></li>
            <li><a href="#">Support</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
        <div class="profile">
            <img src="https://via.placeholder.com/50" alt="User">
            <div class="info">
                <p>John Doe</p>
                <p>john@example.com</p>
            </div>
        </div>
    </div>
    <div class="dashboard">
        <div class="header">
            <h1>Dashboard Overview</h1>
            <div class="user">
                <img src="https://via.placeholder.com/40" alt="User">
            </div>
        </div>
        <div class="cards">
            <div class="card">
                <span class="tag">This Month</span>
                <h2>Data Usage</h2>
                <div class="highlight">250 GB</div>
                <p>75% of 500 GB used</p>
            </div>
            <div class="card">
                <span class="tag">Active</span>
                <h2>Current Speed</h2>
                <div class="highlight">100 Mbps</div>
                <p>Upload: 40 Mbps</p>
            </div>
            <div class="card">
                <span class="tag">Due in 7 days</span>
                <h2>Current Bill</h2>
                <div class="highlight">$59.99</div>
                <a href="#">Pay Now &rarr;</a>
            </div>
            <div class="card">
                <span class="tag">2 Active</span>
                <h2>Support Tickets</h2>
                <div class="highlight">5 Total</div>
                <a href="#">View Tickets &rarr;</a>
            </div>
        </div>
        <div class="data-usage">
            <h3>Data Usage History</h3>
            <div class="chart">
                <div class="bar" style="height: 50%;">
                    <span>250 GB</span>
                </div>
                <div class="bar" style="height: 70%;">
                    <span>350 GB</span>
                </div>
                <div class="bar" style="height: 60%;">
                    <span>300 GB</span>
                </div>
                <div class="bar" style="height: 80%;">
                    <span>400 GB</span>
                </div>
                <div class="bar" style="height: 90%;">
                    <span>450 GB</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
