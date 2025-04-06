<?php
include 'phps/db_connection.php'; // Include your database connection file

// SQL query to fetch plans from the database
$sql = "SELECT planName, planSpeed, planValidity, planPrice FROM plans";
$stmt = $pdo->prepare($sql);
$stmt->execute(); // Execute the SQL query
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all the results in an associative array
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="plan.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>
        <main>

            <h1>Internet Plans</h1>

            <div class="plans">
                <!-- Loop through the fetched plans and display each one -->
                <?php foreach ($plans as $plan): ?>
                    <div class="plan-card">
                        <h3><?= htmlspecialchars($plan['planName']); ?></h3>
                        <p>Speed: <?= htmlspecialchars($plan['planSpeed']); ?></p>
                        <p>Validity: <?= htmlspecialchars($plan['planValidity']); ?></p>
                        <p>₹<?= htmlspecialchars($plan['planPrice']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
    </div>
    </main>
    </div>
</body>

</html>