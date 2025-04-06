`<?php
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
    <link rel="stylesheet" href="updatePlan.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <!-- <link rel="stylesheet" href="cDashboard.css"> -->
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

            <button id="managePlansBtn" class="btn-primary">Manage Plans</button>

            <!-- Modal to add or update plans -->
            <div id="planModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeModal">&times;</span>
                    <div class="tabs">
                        <button class="tab-link active" data-tab="addPlan">Add Plan</button>
                        <button class="tab-link" data-tab="updatePlan">Update Plan</button>
                        <button class="tab-link" data-tab="deletePlan">Delete Plan</button>
                    </div>
                    <div id="addPlan" class="tab-content active">
                        <h2>Add New Plan</h2>
                        <form>
                            <!-- Add Plan Form -->
                            <div class="form-group">
                                <label for="planName">Plan Name:</label>
                                <input type="text" id="planName" required>
                            </div>
                            <div class="form-group">
                                <label for="planPrice">Price:</label>
                                <input type="text" id="planPrice" required>
                            </div>
                            <div class="form-group">
                                <label for="planSpeed">Speed:</label>
                                <input type="text" id="planSpeed" required>
                            </div>
                            <div class="form-group">
                                <label for="planValidity">Validity:</label>
                                <input type="text" id="planValidity" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Plan</button>
                        </form>
                    </div>

                    <div id="updatePlan" class="tab-content">
                        <h2>Update Existing Plan</h2>
                        <form>
                            <!-- Update Plan Form -->
                            <div class="form-group">
                                <label for="selectPlan">Select Plan:</label>
                                <select id="selectPlan">

                                    <!-- this is commented -->
                                    <!-- <?php
                                            foreach ($plans as $plan) : ?>
                                            <option><?= htmlspecialchars($plan['planName']); ?></option>
                                        <?php endforeach; ?> -->

                                    <?php
                                    foreach ($plans as $plan) { ?>
                                        <option><?= htmlspecialchars($plan['planName']); ?></option>
                                    <?php } ?>
                                    <!-- <option value="Basic Plan">Basic Plan</option>
                                    <option value="Standard Plan">Standard Plan</option>
                                    <option value="Premium Plan">Premium Plan</option>
                                    <option value="Ultra Plan">Ultra Plan</option> -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="updatePrice">New Price:</label>
                                <input type="text" id="updatePrice">
                            </div>
                            <div class="form-group">
                                <label for="updateSpeed">New Speed:</label>
                                <input type="text" id="updateSpeed">
                            </div>
                            <div class="form-group">
                                <label for="updateValidity">New Validity:</label>
                                <input type="text" id="updateValidity">
                            </div>
                            <button type="submit" class=" updatebtn btn btn-secondary">Update Plan</button>
                        </form>
                    </div>

                    <div id="deletePlan" class="tab-content">
                        <h2>Delete Plan</h2>
                        <form id="deletePlanForm">
                            <div class="form-group">
                                <label for="selectDeletePlan">Select Plan:</label>
                                <select id="selectDeletePlan" required>
                                    <option value="" disabled selected>Select a Plan to Delete</option>
                                    <?php
                                    foreach ($plans as $plan) {
                                        echo "<option value='" . htmlspecialchars($plan['planName']) . "'>" . htmlspecialchars($plan['planName']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger">Delete Plan</button>
                        </form>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const managePlansBtn = document.getElementById("managePlansBtn");
            const planModal = document.getElementById("planModal");
            const closeModal = document.getElementById("closeModal");
            const tabLinks = document.querySelectorAll(".tab-link");
            const tabContents = document.querySelectorAll(".tab-content");
            const addPlanForm = document.querySelector("#addPlan form");
            const updatePlanForm = document.querySelector("#updatePlan form"); // Update form reference
            const plansContainer = document.querySelector(".plans");
            const deletePlanForm = document.getElementById("deletePlanForm");
            const selectDeletePlan = document.getElementById("selectDeletePlan");

            // Open the modal when the button is clicked
            managePlansBtn.addEventListener("click", () => {
                planModal.style.display = "flex";
            });

            // Close the modal when the close button is clicked
            closeModal.addEventListener("click", () => {
                planModal.style.display = "none";
            });

            // Switch between the "Add Plan" and "Update Plan" tabs
            tabLinks.forEach((link) => {
                link.addEventListener("click", () => {
                    tabLinks.forEach((tab) => tab.classList.remove("active"));
                    link.classList.add("active");

                    tabContents.forEach((content) => content.classList.remove("active"));
                    document.getElementById(link.dataset.tab).classList.add("active");
                });
            });

            // Close the modal if the user clicks outside the modal
            window.addEventListener("click", (event) => {
                if (event.target === planModal) {
                    planModal.style.display = "none";
                }
            });

            //======================================================Add Plan Btn===========================================

            // Handle the Add Plan form submission with AJAX
            addPlanForm.addEventListener("submit", (event) => {
                event.preventDefault();

                // Retrieve form data
                const planName = document.getElementById("planName").value.trim();
                const planPrice = document.getElementById("planPrice").value.trim();
                const planSpeed = document.getElementById("planSpeed").value.trim();
                const planValidity = document.getElementById("planValidity").value.trim();

                // Check if all fields are filled
                if (planName && planPrice && planSpeed && planValidity) {
                    // Create FormData object to send data via AJAX
                    const formData = new FormData();
                    formData.append("planName", planName);
                    formData.append("planPrice", planPrice);
                    formData.append("planSpeed", planSpeed);
                    formData.append("planValidity", planValidity);

                    // Send data using fetch API
                    fetch("phps/insertIntoPlan.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            console.log(data);
                            if (data === "success") {
                                // Refresh the page after success
                                alert("Plan added successfully!");
                                location.reload();
                            } else if (data === "Name already exists") {
                                alert("Plan already exists. Please try again.");
                            } else {
                                console.error('Failed to add plan: ', data);
                                alert("There was an error adding the plan.");
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error: ', error);
                            alert("Error: " + error);
                        });


                } else {
                    alert("Please fill out all fields.");
                }
            });


            //==========================================================Update Plan Btn==================================================


            // Handle the Update Plan form submission with AJAX
            updatePlanForm.addEventListener("submit", (event) => {
                event.preventDefault();

                // Retrieve the selected plan and new values
                const oldPlanName = document.getElementById("selectPlan").selectedOptions[0].text.trim();
                const planPrice = document.getElementById("updatePrice").value.trim();
                const planSpeed = document.getElementById("updateSpeed").value.trim();
                const planValidity = document.getElementById("updateValidity").value.trim();

                // Check if all fields are filled
                if (planPrice && planSpeed && planValidity) {
                    // Create FormData object for update
                    const formData = new FormData();
                    formData.append("planPrice", planPrice);
                    formData.append("planSpeed", planSpeed);
                    formData.append("planValidity", planValidity);
                    formData.append("oldPlanName", oldPlanName); // Send the old plan name for update

                    // Send data using fetch API
                    fetch("phps/updateToPlans.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data === "success") {
                                // Reset the form and close the modal
                                // updatePlanForm.reset();
                                planModal.style.display = "none";
                                location.reload();
                                alert("Plan updated successfully.");
                            } else {
                                alert("There was an error updating the plan.");
                            }
                        })
                        .catch(error => {
                            alert("Error: " + error);
                        });
                } else {
                    alert("Please fill out all fields.");
                }
            });

            //=================================================delete plan btn==========================================


            // Handle the Delete Plan form submission with AJAX
            deletePlanForm.addEventListener("submit", (event) => {
                event.preventDefault();

                // Retrieve the selected plan
                const planNameToDelete = selectDeletePlan.value.trim();

                // Check if a plan is selected
                if (planNameToDelete) {
                    // Create FormData object to send data via AJAX
                    const formData = new FormData();
                    formData.append("planNameToDelete", planNameToDelete);

                    // Send data using fetch API
                    fetch("phps/deleteFromPlans.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data === "success") {
                                // Plan was successfully deleted
                                alert("Plan deleted successfully.");
                                location.reload(); // Reload the page to reflect changes
                            } else {
                                alert("There was an error deleting the plan.");
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error: ', error); // Log fetch errors
                            alert("Error: " + error);
                        });
                } else {
                    alert("Please select a plan to delete.");
                }
            });
        });
    </script>

    <script>
        document.getElementById("selectPlan").addEventListener("change", (event) => {
    const selectedPlan = event.target.value;

    if (selectedPlan) {
        // Send AJAX request to fetch plan details
        fetch(`phps/fetchPlanDetails.php?planName=${encodeURIComponent(selectedPlan)}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    // Populate the form fields with the fetched data
                    document.getElementById("updatePrice").value = data.planPrice;
                    document.getElementById("updateSpeed").value = data.planSpeed;
                    document.getElementById("updateValidity").value = data.planValidity;
                } else {
                    alert("Failed to fetch plan details.");
                }
            })
            .catch(error => {
                console.error("Fetch error: ", error);
                alert("Error: " + error);
            });
    }
});

    </script>

</body>

</html>