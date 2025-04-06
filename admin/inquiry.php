<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry</title>
    <link rel="stylesheet" href="inquiry.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>
        <main>
            <h1>Inquiries</h1>

            <!-- Filter Section -->
            <div class="filter-container">
                <input type="text" id="filterInput" class="filter-input" placeholder="🔍 Search by Name, Phone, or Email">
                <button id="clearBtn" class="clear-btn">Clear</button>
            </div>

            <div class="recent-orders">
                <h2>Recent Inquiries</h2>
                <table id="inquiryTable">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Person Name</th>
                            <th>Phone No</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'phps/db_connection.php';
                        try {
                            $query = "SELECT * FROM enquiry ORDER BY timespan DESC";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();
                            $srNo = 1;
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$srNo}</td>
                                        <td>{$row['personName']}</td>
                                        <td>{$row['phoneNo']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['address']}</td>
                                        <td>{$row['timespan']}</td>
                                      </tr>";
                                $srNo++;
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='6'>Error fetching inquiries: " . $e->getMessage() . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        const filterInput = document.getElementById('filterInput');
        const tableRows = document.querySelectorAll('#inquiryTable tbody tr');
        const clearBtn = document.getElementById('clearBtn');

        // Filter Table Function
        filterInput.addEventListener('keyup', () => {
            const filterValue = filterInput.value.toLowerCase();

            tableRows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const phone = row.cells[2].textContent.toLowerCase();
                const email = row.cells[3].textContent.toLowerCase();

                if (name.includes(filterValue) || phone.includes(filterValue) || email.includes(filterValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Clear Filter Button
        clearBtn.addEventListener('click', () => {
            filterInput.value = '';
            tableRows.forEach(row => {
                row.style.display = '';
            });
            filterInput.focus();
        });
    </script>
</body>

</html>
