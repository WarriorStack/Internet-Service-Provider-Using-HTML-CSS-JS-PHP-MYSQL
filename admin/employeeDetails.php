<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees</title>
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
    <style>
        /* Filter Section Styling */
        .filter-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            background: #f4f4f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .filter-container input, .filter-container select, .filter-container button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .filter-container input:focus,
        .filter-container select:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
        }

        .filter-container button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .filter-container button:hover {
            background-color: #0056b3;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f4f4f9;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>

        <main>
            <h1>Employee Details</h1>

            <!-- Filter Section -->
            <div class="filter-container">
                <input type="text" id="filterText" placeholder="Search by ID, Name, Role" onkeyup="filterTable()">
                <select id="filterStatus" onchange="filterTable()">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <button onclick="clearFilters()">Clear Filters</button>
            </div>

            <div class="recent-orders">
                <h2>Employee Records</h2>
                <table id="employeeTable">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Salary</th>
                            <th>Salary to Pay</th>
                            <th>Date Joined</th>
                            <th>Last Paid Date</th>
                            <th>Contact</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'phps/db_connection.php';
                        try {
                            $query = "SELECT e.empId, r.cName, e.empRole, e.empStatus, e.empSalary, e.salaryToPay, e.dateJoined, e.lastPaidDate, r.cContactNo, r.cEmail 
                                      FROM employee e 
                                      INNER JOIN rginfo r ON e.cId = r.cId 
                                      ORDER BY e.empId DESC";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();
                            $srNo = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$srNo}</td>
                                        <td>{$row['empId']}</td>
                                        <td>{$row['cName']}</td>
                                        <td>{$row['empRole']}</td>
                                        <td>{$row['empStatus']}</td>
                                        <td>{$row['empSalary']}</td>
                                        <td>{$row['salaryToPay']}</td>
                                        <td>{$row['dateJoined']}</td>
                                        <td>{$row['lastPaidDate']}</td>
                                        <td>{$row['cContactNo']}</td>
                                        <td>{$row['cEmail']}</td>
                                      </tr>";
                                $srNo++;
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='11'>Error fetching employee records: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function filterTable() {
            const filterText = document.getElementById('filterText').value.toLowerCase();
            const filterStatus = document.getElementById('filterStatus').value.toLowerCase();
            const table = document.getElementById('employeeTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const id = rows[i].cells[1].textContent.toLowerCase();
                const name = rows[i].cells[2].textContent.toLowerCase();
                const role = rows[i].cells[3].textContent.toLowerCase();
                const status = rows[i].cells[4].textContent.toLowerCase();

                const matchesText = id.includes(filterText) || name.includes(filterText) || role.includes(filterText);
                const matchesStatus = filterStatus === '' || status === filterStatus;

                if (matchesText && matchesStatus) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        function clearFilters() {
            document.getElementById('filterText').value = '';
            document.getElementById('filterStatus').value = '';
            filterTable(); // Refresh the table
        }
    </script>
</body>

</html>
