<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details - Admin - ISP</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="customerDetail.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>
<body>
    <div class="container">
    <?php include 'http://localhost/ISPProject/admin/css/dashboard.php'?>
        <div class="recent-orders">
            <h2>Recent Orders</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Person Name</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Time</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Om</td>
                            <td class="warning">Recharge</td>
                            <td>500</td>
                            <td>12.55</td>
                            <td class="primary">Details</td>
                        </tr>
                    </tbody>
                </table>
                <a href="#">Show all</a>
            </div>
        </div>
</body>
</html>