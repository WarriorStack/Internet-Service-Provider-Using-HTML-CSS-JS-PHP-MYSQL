<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry</title>
    <link rel="stylesheet" href="complaints.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>

    <div class="container">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/ISPProject/admin/css/dashboard2.php'; ?>
        <main>
            <h1>Inquiries</h1>
            <div class="recent-orders">
                <h2>Complaints</h2>
                <!-- Filter Section -->
                <div class="filter-container">
                    <input type="text" id="filterCustomerId" placeholder="Search by Customer ID" onkeyup="filterTable()">
                    <select id="filterStatus" onchange="filterTable()">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Resolved">Resolved</option>
                        <option value="In Progress">In Progress</option>
                    </select>
                    <input type="date" id="filterDate" onchange="filterTable()">
                </div>
                <table id="complaintsTable">
    <thead>
        <tr>
            <th>Sr.No</th>
            <th>Complaint Id</th>
            <th>Customer Id</th>
            <th>Details</th>
            <th>Status</th>
            <th>Date</th>
            <th>Update</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'phps/db_connection.php';
        try {
            $query = "SELECT * FROM complaints ORDER BY Id DESC";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $srNo = 1;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$srNo}</td>
                    <td>{$row['Id']}</td>
                    <td>{$row['CustomerId']}</td>
                    <td>{$row['Details']}</td>
                    <td id='status-{$row['Id']}'>{$row['Status']}</td>
                    <td>{$row['Date']}</td>
                    <td>
                        <button class='update-btn' data-id='{$row['Id']}' data-status='{$row['Status']}'>Update</button>
                    </td>
                </tr>";
                $srNo++;
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='7'>Error fetching complaints: " . $e->getMessage() . "</td></tr>";
        }
        ?>
    </tbody>
</table>

            </div>
        </main>
    </div>

    <!-- Popup Overlay -->
    <div class="popup-overlay" id="popupOverlay"></div>

    <!-- Popup Form -->
    <div class="popup" id="popupForm">
        <h2>Update Complaint Status</h2>
        <form id="updateComplaintForm">
            <input type="hidden" id="complaintId" name="complaintId">
            <select id="status" name="status" required>
                <option value="Pending">Pending</option>
                <option value="Resolved">Resolved</option>
                <option value="In Progress">In Progress</option>
            </select>
            <button type="submit">Update</button>
            <button type="button" onclick="closePopup()">Cancel</button>
        </form>
    </div>

    <script>
        // Open Popup
        document.querySelectorAll('.update-btn').forEach(button => {
            button.addEventListener('click', () => {
                const complaintId = button.getAttribute('data-id');
                const currentStatus = button.getAttribute('data-status');

                document.getElementById('complaintId').value = complaintId;
                document.getElementById('status').value = currentStatus;

                document.getElementById('popupOverlay').style.display = 'block';
                document.getElementById('popupForm').style.display = 'block';
            });
        });

        // Close Popup
        function closePopup() {
            document.getElementById('popupOverlay').style.display = 'none';
            document.getElementById('popupForm').style.display = 'none';
        }

        // Handle Form Submission via AJAX
        document.getElementById('updateComplaintForm').addEventListener('submit', (e) => {
            e.preventDefault();

            const complaintId = document.getElementById('complaintId').value;
            const status = document.getElementById('status').value;

            fetch('phps/update_complaint.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `complaintId=${encodeURIComponent(complaintId)}&status=${encodeURIComponent(status)}`
                })
                .then(response => response.text())
                .then(response => {
                    if (response === 'success') {
                        document.getElementById(`status-${complaintId}`).innerText = status;
                        closePopup();
                    } else {
                        alert('Failed to update complaint');
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>

    <script>
function filterTable() {
    const customerIdFilter = document.getElementById("filterCustomerId").value.toLowerCase();
    const statusFilter = document.getElementById("filterStatus").value.toLowerCase();
    const dateFilter = document.getElementById("filterDate").value;

    const table = document.getElementById("complaintsTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        const customerId = rows[i].cells[2].innerText.toLowerCase();
        const status = rows[i].cells[4].innerText.toLowerCase();
        const date = rows[i].cells[5].innerText;

        let showRow = true;

        // Filter by customer ID
        if (customerIdFilter && !customerId.includes(customerIdFilter)) {
            showRow = false;
        }

        // Filter by status
        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }

        // Filter by date
        if (dateFilter && date !== dateFilter) {
            showRow = false;
        }

        rows[i].style.display = showRow ? "" : "none";
    }
}
</script>


</body>

</html>