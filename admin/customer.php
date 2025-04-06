<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>

        <main>
            <h1>Customer Details</h1>

            <!-- 🔎 Filter Section -->
            <div class="filter-container">
                <input type="text" id="searchName" placeholder="Search by Name" onkeyup="filterTable()">
                <select id="filterRole" onchange="filterTable()">
                    <option value="">All Roles</option>
                    <option value="Customer">Customer</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>

            <div class="recent-orders">
                <h2>Customer Details</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Contact No</th>
                            <th>Answer</th>
                            <th>Registrations Date</th>
                            <th>Mac Address</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'phps/db_connection.php';
                        try {
                            $query = "SELECT * FROM rginfo WHERE Role='Customer' ORDER BY cId DESC";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();
                            $srNo = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$srNo}</td>
                                        <td>{$row['cId']}</td>
                                        <td>{$row['cName']}</td>
                                        <td>{$row['cAddress']}</td>
                                        <td>{$row['cEmail']}</td>
                                        <td>{$row['cPassword']}</td>
                                        <td>{$row['cContactNo']}</td>
                                        <td>{$row['cAns']}</td>
                                        <td>{$row['rgDate']}</td>
                                        <td>{$row['cMacAddress']}</td>
                                        <td>{$row['Role']}</td>
                                      </tr>";
                                $srNo++;
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='11'>Error fetching customer data: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        // ✅ Modern Filter Function
        function filterTable() {
            const searchName = document.getElementById("searchName").value.toLowerCase();
            const filterRole = document.getElementById("filterRole").value.toLowerCase();
            const rows = document.querySelectorAll("table tbody tr");

            rows.forEach(row => {
                const name = row.cells[2].textContent.toLowerCase();
                const role = row.cells[10].textContent.toLowerCase();

                // Apply filter conditions
                if ((name.includes(searchName) || searchName === "") &&
                    (role.includes(filterRole) || filterRole === "")) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
</body>

</html>
