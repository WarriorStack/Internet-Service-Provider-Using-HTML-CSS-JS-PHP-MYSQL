<?php

include 'phps/db_connection.php'; // Ensure this file correctly establishes a database connection

// Fetch plans from the database
$sql = "SELECT * FROM plans";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetching plans data
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans</title>
    <link rel="stylesheet" href="updatePlan.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body>
    <div class="container">
        <?php include 'css/dashboard.php'; ?>
        <main>
            <h1>Internet Plans</h1>

            <div class="plans">
                <?php foreach ($plans as $plan): ?>
                    <div class="plan-card">
                        <h2><?= htmlspecialchars($plan['planId']); ?></h2>
                        <h3><?= htmlspecialchars($plan['planName']); ?></h3>
                        <p>Speed: <?= htmlspecialchars($plan['planSpeed']); ?></p>
                        <p>Validity: <?= htmlspecialchars($plan['planValidity']); ?></p>
                        <span>₹</span>
                        <p><?= htmlspecialchars($plan['planPrice']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <button id="proceedToPayBtn" class="btn-success">Proceed to Pay</button>

            <div class="plan-active-popup" id="planActivePopup">
                <div class="plan-active-content">
                    <h3>⚠ Active Plan Alert</h3>
                    <p>You already have an active plan until <span id="planExpiryDate"></span>.</p>
                    <button onclick="closePlanActivePopup()">OK</button>
                </div>
            </div>

            <!-- Payment Popup -->
            <div class="payment-popup" id="paymentPopup">
                <div class="payment-popup-content">
                    <h2>Recharge - <span id="selectedPlanName"></span></h2>
                    <form id="paymentForm">
                        <label for="paymentMethod">Payment Method:</label>
                        <select id="paymentMethod" name="paymentMethod" required>
                            <option value="">Select Payment Method</option>
                            <option value="creditCard">Credit Card</option>
                            <option value="debitCard">Debit Card</option>
                            <option value="upi">UPI</option>
                            <option value="netBanking">Net Banking</option>
                            <option value="qrCode">QR Code</option>
                        </select>

                        <!-- Credit/Debit Card Fields -->
                        <div id="creditDebitCardFields" class="payment-method-fields">
                            <label for="cardNumber">Card Number:</label>
                            <input type="text" id="cardNumber" name="cardNumber" maxlength="12" required>

                            <label for="expiryDate">Expiry Date:</label>
                            <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY" required>

                            <label for="cvv">CVV:</label>
                            <input type="text" id="cvv" name="cvv" maxlength="3" required>
                        </div>

                        <!-- UPI Fields -->
                        <div id="upiFields" class="payment-method-fields">
                            <label for="upiId">UPI ID:</label>
                            <input type="text" id="upiId" name="upiId" maxlength="12" required>
                        </div>

                        <!-- Net Banking Fields -->
                        <div id="netBankingFields" class="payment-method-fields">
                            <label for="bankName">Bank Name:</label>
                            <input type="text" id="bankName" name="bankName" required>
                        </div>

                        <!-- QR Code Fields -->
                        <div id="qrCodeFields" class="payment-method-fields">
                            <p>Scan the QR code to complete the payment.</p>
                            <img src="https://via.placeholder.com/150" alt="QR Code">
                        </div>

                        <button type="submit" class="btn-primary">Pay Now</button>
                    </form>

                    <div class="payment-success" id="paymentSuccess">
                        <h3>Payment Successful!</h3>
                        <p>Your payment has been processed successfully.</p>
                    </div>

                    <div class="payment-failure" id="paymentFailure">
                        <h3>Payment Failed!</h3>
                        <p>Please try again or use a different payment method.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php
    if (isset($_GET['invoice'])) {
        $invoicePath = $_GET['invoice'];
        if (file_exists($invoicePath)) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($invoicePath) . '"');
            readfile($invoicePath);
            exit;
        } else {
            echo "Invoice not found.";
        }
    }
    ?>



    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const planCards = document.querySelectorAll(".plan-card");
            const proceedToPayBtn = document.getElementById("proceedToPayBtn");
            const paymentPopup = document.getElementById("paymentPopup");
            const selectedPlanName = document.getElementById("selectedPlanName");
            const paymentForm = document.getElementById("paymentForm");
            const paymentMethod = document.getElementById("paymentMethod");
            const paymentSuccess = document.getElementById("paymentSuccess");
            const paymentFailure = document.getElementById("paymentFailure");

            const creditDebitCardFields = document.getElementById("creditDebitCardFields");
            const upiFields = document.getElementById("upiFields");
            const netBankingFields = document.getElementById("netBankingFields");
            const qrCodeFields = document.getElementById("qrCodeFields");

            let selectedPlan = null;

            planCards.forEach(card => {
                card.addEventListener("click", () => {
                    planCards.forEach(card => card.classList.remove("selected"));
                    card.classList.add("selected");

                    selectedPlan = {
                        planId: card.querySelector("h2").innerText,
                        planName: card.querySelector("h3").innerText,
                        price: parseFloat(card.querySelector("p:last-of-type").innerText.replace("$", ""))
                    };

                    console.log("Selected Plan:", selectedPlan);
                });
            });

            proceedToPayBtn.addEventListener("click", () => {
                if (!selectedPlan) {
                    alert("Please select a plan.");
                    return;
                }
                // Removed the fetch call for checking expiry
                selectedPlanName.innerText = selectedPlan.planName;
                paymentPopup.style.display = "flex";
            });

            paymentMethod.addEventListener("change", () => {
                const method = paymentMethod.value;

                document.querySelectorAll(".payment-method-fields").forEach(field => {
                    field.classList.remove("active");
                    field.querySelectorAll("input").forEach(input => input.disabled = true);
                });

                if (method === "creditCard" || method === "debitCard") {
                    creditDebitCardFields.classList.add("active");
                    creditDebitCardFields.querySelectorAll("input").forEach(input => input.disabled = false);
                } else if (method === "upi") {
                    upiFields.classList.add("active");
                    document.getElementById("upiId").disabled = false;
                } else if (method === "netBanking") {
                    netBankingFields.classList.add("active");
                    document.getElementById("bankName").disabled = false;
                } else if (method === "qrCode") {
                    qrCodeFields.classList.add("active");
                }
            });



            paymentForm.addEventListener("submit", (e) => {
                e.preventDefault();
                console.log("Form submitted!"); // Add this line to check if the form is being submitted
                // Validate form data
                if (!validateForm()) {
                    return;
                }

                // Simulate payment processing
                setTimeout(() => {
                    paymentForm.style.display = "none";
                    paymentSuccess.style.display = "block";

                    // Simulate adding data to transaction and account info tables
                    addTransaction(selectedPlan);

                    // Hide the popup after 3 seconds
                    setTimeout(() => {
                        paymentPopup.style.display = "none";
                        paymentForm.style.display = "block";
                        paymentSuccess.style.display = "none";
                    }, 3000);
                }, 2000);
            });

            function addTransaction(plan) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "phps/addTransaction.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText);
                    } else {
                        console.error("Failed to add transaction.");
                    }
                };
                xhr.onerror = function() {
                    console.error("Request failed.");
                };
                xhr.send("planId=" + encodeURIComponent(plan.planId) + "&amount=" + encodeURIComponent(plan.price));
            }


            function validateForm() {
                const method = paymentMethod.value;

                if (method === "creditCard" || method === "debitCard") {
                    const cardNumber = document.getElementById("cardNumber").value.trim();
                    const expiryDate = document.getElementById("expiryDate").value.trim();
                    const cvv = document.getElementById("cvv").value.trim();

                    const cardNumberRegex = /^\d{12}$/;
                    const expiryDateRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
                    const cvvRegex = /^\d{3}$/;

                    if (!cardNumber.match(cardNumberRegex)) {
                        alert("Invalid card number! It should be exactly 12 digits.");
                        return false;
                    }
                    if (!expiryDate.match(expiryDateRegex)) {
                        alert("Invalid expiry date! Use MM/YY format.");
                        return false;
                    }
                    if (!cvv.match(cvvRegex)) {
                        alert("Invalid CVV! It should be exactly 3 digits.");
                        return false;
                    }
                } else if (method === "upi") {
                    const upiId = document.getElementById("upiId").value.trim();
                    if (!upiId.includes("@")) {
                        alert("Invalid UPI ID! It should contain '@'.");
                        return false;
                    }
                } else if (method === "netBanking") {
                    const bankName = document.getElementById("bankName").value.trim();
                    if (!bankName) {
                        alert("Please enter your bank name.");
                        return false;
                    }
                }
                return true;
            }

            function addTransaction(plan) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "phps/addTransaction.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText);
                    } else {
                        console.error("Failed to add transaction.");
                    }
                };
                xhr.onerror = function() {
                    console.error("Request failed.");
                };
                xhr.send("planId=" + encodeURIComponent(plan.planId) + "&amount=" + encodeURIComponent(plan.price));
            }

        });
    </script>

    <script>
        function closePlanActivePopup() {
            document.getElementById("planActivePopup").classList.remove("show");
        }
    </script>
</body>

</html>