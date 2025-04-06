<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan - SpeedNet</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="Plan.css">
    <link rel="stylesheet" href="../main.css">
</head>

<body>
    <div class="darkShaded">
        <div id="Plan" class="secondDark">
            <div class="upperText">
                <h2>Choose Your Perfect Plan</h2>
                <p>Flexible plans for every need and budget</p>
            </div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php
                    
                    include 'php/connector.php';
                    // Fetch all plans
                    $sql = "SELECT * FROM plans";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="swiper-slide plan">';
                            echo '<h2>₹' . htmlspecialchars($row['planPrice']) . '/month</h2>';
                            echo '<p>' . htmlspecialchars($row['planName']) . '</p>';
                            echo '<ul>';
                            echo '<li>' . htmlspecialchars($row['planSpeed']) . ' Speed</li>';
                            echo '<li>Validity: ' . htmlspecialchars($row['planValidity']) . ' Days</li>';
                            echo '</ul>';
                            echo '<a href="#" class="btn">Select Plan</a>';
                            echo '</div>';
                        }
                    } else {
                        echo "<p>No plans available.</p>";
                    }

                    $conn->close();
                    ?>
                </div>

                <!-- Navigation Buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>
            </div>


        </div>
    </div>

   <!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
    const swiper = new Swiper(".mySwiper", {
        loop: false,
        slidesPerView: 3,
        spaceBetween: 20,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            320: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
</script>

</body>

</html>