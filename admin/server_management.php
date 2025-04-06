<?php
if (isset($_GET['delete']) && $_GET['delete'] == 'success') {
    echo "<p style='color: green; font-weight: bold;'>Server deleted successfully!</p>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Management</title>
    <link rel="stylesheet" href="server_management.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="container">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>

        <main>
            <h1>Server Management</h1>
            <div class="server-form">
                <h2>Add / Update Server</h2>
                <form action="phps/server_process.php" method="POST">
                    <input type="hidden" name="serverId" id="serverId">
                    <label for="serverName">Server Name:</label>
                    <input type="text" name="serverName" id="serverName" required>

                    <label for="location">Location:</label>
                    <input type="text" name="location" id="location" required>

                    <label for="capacity">Total Capacity (TB):</label>
                    <input type="number" step="0.01" name="capacity" id="capacity" required>

                    <label for="usedCapacity">Used Capacity (TB):</label>
                    <input type="number" step="0.01" name="usedCapacity" id="usedCapacity" required>

                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="active">Active</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="offline">Offline</option>
                    </select>

                    <button type="submit">Save Server</button>
                </form>
            </div>

            <div class="server-list recent-orders">
                <h2>Server Resources</h2>
                <div class="server-list recent-orders">
                    <h2>Server Resources</h2>

                    <!-- ✅ Filter Input -->
                    <div class="filter-container">
                        <input type="text" id="filterInput" placeholder="Search by Name, Location, or Status">
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Server ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Total Capacity</th>
                                <th>Used Capacity</th>
                                <th>Available Capacity</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="serverTableBody">
                            <?php
                            include 'phps/db_connection.php';
                            try {
                                $query = "SELECT serverId, serverName, location, capacity, usedCapacity, 
                          (capacity - usedCapacity) AS availableCapacity, status 
                          FROM server_resources ORDER BY serverId DESC";
                                $stmt = $pdo->prepare($query);
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                            <td>{$row['serverId']}</td>
                            <td>{$row['serverName']}</td>
                            <td>{$row['location']}</td>
                            <td>{$row['capacity']} TB</td>
                            <td>{$row['usedCapacity']} TB</td>
                            <td>{$row['availableCapacity']} TB</td>
                            <td>{$row['status']}</td>
                            <td>
                                <button class='edit-btn' 
                                        data-id='{$row['serverId']}'
                                        data-name='{$row['serverName']}'
                                        data-location='{$row['location']}'
                                        data-capacity='{$row['capacity']}'
                                        data-usedcapacity='{$row['usedCapacity']}'
                                        data-status='{$row['status']}'>
                                    Edit
                                </button>
                                <a href='phps/server_delete.php?id={$row['serverId']}' class='delete-btn'>Delete</a>
                            </td>
                          </tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='8'>Error fetching server data: " . $e->getMessage() . "</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Handle edit button click
            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function() {
                    document.getElementById('serverId').value = this.dataset.id;
                    document.getElementById('serverName').value = this.dataset.name;
                    document.getElementById('location').value = this.dataset.location;
                    document.getElementById('capacity').value = this.dataset.capacity;
                    document.getElementById('usedCapacity').value = this.dataset.usedcapacity;
                    document.getElementById('status').value = this.dataset.status;
                });
            });

            // Confirm delete
            document.querySelectorAll(".delete-btn").forEach(link => {
                link.addEventListener("click", function(event) {
                    if (!confirm("Are you sure?")) {
                        event.preventDefault(); // Stop the link from executing if canceled
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleDarkMode = document.getElementById("dark-mode-toggle"); // Add a button with this ID
            toggleDarkMode.addEventListener("click", function() {
                document.body.classList.toggle("dark");
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Confirm delete
            document.querySelectorAll(".delete-btn").forEach(link => {
                link.addEventListener("click", function(event) {
                    if (!confirm("Are you sure you want to delete this server?")) {
                        event.preventDefault(); // Stop the link from executing if canceled
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterInput = document.getElementById('filterInput');
            const tableRows = document.querySelectorAll('#serverTableBody tr');

            filterInput.addEventListener('keyup', () => {
                const filterValue = filterInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const name = row.children[1].textContent.toLowerCase();
                    const location = row.children[2].textContent.toLowerCase();
                    const status = row.children[6].textContent.toLowerCase();

                    if (name.includes(filterValue) || location.includes(filterValue) || status.includes(filterValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>

</html>