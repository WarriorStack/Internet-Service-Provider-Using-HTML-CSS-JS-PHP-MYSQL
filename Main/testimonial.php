<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials - ISP</title>
    <link rel="stylesheet" href="testimonial.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>

<body>

    <div class="darkShaded">
        <section id="testimonials">
            <div class="testimonial-header">
                <h2>What Our Customers Say</h2>
                <p>Real experiences from our satisfied customers</p>
            </div>

            <div class="swiper testimonial-slider">
                <div class="swiper-wrapper">
                    <!-- Review 1 -->
                    <div class="swiper-slide testimonial-card">
                        <div class="reviewer-info">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                            <div>
                                <h3>John Doe</h3>
                                <div class="stars">⭐⭐⭐⭐⭐</div>
                            </div>
                        </div>
                        <p>"Amazing service! SpeedNet has provided me with the best internet experience so far. Highly recommended!"</p>
                    </div>

                    <!-- Review 2 -->
                    <div class="swiper-slide testimonial-card">
                        <div class="reviewer-info">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                            <div>
                                <h3>Sarah Johnson</h3>
                                <div class="stars">⭐⭐⭐⭐</div>
                            </div>
                        </div>
                        <p>"Good connection and support, but there are occasional slowdowns during peak hours."</p>
                    </div>

                    <!-- Review 3 -->
                    <div class="swiper-slide testimonial-card">
                        <div class="reviewer-info">
                            <img src="https://randomuser.me/api/portraits/men/51.jpg" alt="User">
                            <div>
                                <h3>Michael Lee</h3>
                                <div class="stars">⭐⭐⭐⭐⭐</div>
                            </div>
                        </div>
                        <p>"Absolutely love the speed and stability of my connection. Best decision to switch to SpeedNet!"</p>
                    </div>

                    <!-- Review 4 -->
                    <div class="swiper-slide testimonial-card">
                        <div class="reviewer-info">
                            <img src="https://randomuser.me/api/portraits/women/29.jpg" alt="User">
                            <div>
                                <h3>Lisa Brown</h3>
                                <div class="stars">⭐⭐⭐⭐</div>
                            </div>
                        </div>
                        <p>"Great service and customer support. Installation was super quick!"</p>
                    </div>

                    <!-- Review 5 -->
                    <div class="swiper-slide testimonial-card">
                        <div class="reviewer-info">
                            <img src="https://randomuser.me/api/portraits/men/40.jpg" alt="User">
                            <div>
                                <h3>Chris Evans</h3>
                                <div class="stars">⭐⭐⭐⭐⭐</div>
                            </div>
                        </div>
                        <p>"No issues so far, speed is great, and it's worth every penny!"</p>
                    </div>







                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            fetch('php/get_reviews.php') // Ensure path is correct
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Fetched data:', data); // Debugging output

                                    if (data.success) {
                                        const reviews = data.reviews;
                                        const swiperWrapper = document.querySelector('.swiper-wrapper');

                                        // ✅ Append fetched reviews instead of replacing static ones
                                        reviews.forEach(review => {
                                            const ratingStars = '⭐'.repeat(Number(review.rating));
                                            const profilePic = "/ISPPROJECT/" + review.profile_pic;
                                            const reviewCard = `
        <div class="swiper-slide testimonial-card">
            <div class="reviewer-info">
                <img src="${profilePic}" alt="User">
                <div> 
                    <h3>${review.name}</h3>
                    <div class="stars">${ratingStars}</div>
                </div>
            </div>
            <p>"${review.message}"</p>
        </div>
    `;
                                            swiperWrapper.insertAdjacentHTML('beforeend', reviewCard);
                                        });



                                        // ✅ Reinitialize Swiper after adding new slides
                                        new Swiper(".testimonial-slider", {
                                            slidesPerView: 3,
                                            spaceBetween: 20,
                                            loop: true,
                                            grabCursor: true,
                                            centeredSlides: true,
                                            autoplay: {
                                                delay: 3000,
                                                disableOnInteraction: false,
                                            },
                                            navigation: {
                                                nextEl: ".swiper-button-next",
                                                prevEl: ".swiper-button-prev",
                                            },
                                            pagination: {
                                                el: ".swiper-pagination",
                                                clickable: true,
                                            },
                                            breakpoints: {
                                                1024: {
                                                    slidesPerView: 3
                                                },
                                                768: {
                                                    slidesPerView: 2
                                                },
                                                480: {
                                                    slidesPerView: 1
                                                }
                                            }
                                        });
                                    } else {
                                        console.error('Failed to load reviews:', data.message);
                                    }
                                })
                                .catch(error => console.error('Error fetching reviews:', error));
                        });
                    </script>

                </div>

                <!-- Navigation Arrows -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

                <!-- Pagination Dots -->
                <div class="swiper-pagination"></div>
                <!-- Leave a Review Button -->
                <div class="leave-review">
                    <p>Want to share your experience?</p>
                    <button id="openReviewPopup" class="btn-review">Leave a Review</button>
                </div>

                <!-- Review Popup Modal -->
                <div id="reviewPopup" class="review-modal">
                    <div class="review-card">
                        <span class="close-btn">&times;</span>
                        <h2>Leave a Review</h2>

                        <!-- Star Rating System -->
                        <div class="stars">
                            <form id="reviewForm">
                                <input class="star star-5" id="star-5" type="radio" name="star" value="5" />
                                <label class="star star-5" for="star-5"></label>

                                <input class="star star-4" id="star-4" type="radio" name="star" value="4" />
                                <label class="star star-4" for="star-4"></label>

                                <input class="star star-3" id="star-3" type="radio" name="star" value="3" />
                                <label class="star star-3" for="star-3"></label>

                                <input class="star star-2" id="star-2" type="radio" name="star" value="2" />
                                <label class="star star-2" for="star-2"></label>

                                <input class="star star-1" id="star-1" type="radio" name="star" value="1" />
                                <label class="star star-1" for="star-1"></label>
                            </form>
                        </div>

                        <!-- Login Fields -->
                        <input type="text" id="userId" placeholder="Enter your ID">
                        <input type="password" id="userPassword" placeholder="Enter your Password">
                        <!-- Add this inside the review popup -->
                        <textarea id="reviewMessage" placeholder="Write your review here..."></textarea>


                        <!-- Submit Button -->
                        <button id="submitReview" class="btn-submit">Send Review</button>
                    </div>
                </div>



        </section>

    </div>




    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var swiper = new Swiper(".testimonial-slider", {
                slidesPerView: 3, // Show 3 cards at a time
                spaceBetween: 20, // Gap between cards
                loop: true, // Infinite loop
                grabCursor: true, // Makes the slider interactive
                centeredSlides: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    1024: {
                        slidesPerView: 3
                    },
                    768: {
                        slidesPerView: 2
                    },
                    480: {
                        slidesPerView: 1
                    }
                }
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const reviewPopup = document.getElementById("reviewPopup");
            const openPopupBtn = document.getElementById("openReviewPopup");
            const closePopupBtn = document.querySelector(".close-btn");
            const submitReviewBtn = document.getElementById("submitReview");

            const responsePopup = document.createElement("div");
            responsePopup.classList.add("response-popup");
            document.body.appendChild(responsePopup);

            openPopupBtn.addEventListener("click", function() {
                reviewPopup.style.display = "flex";
            });

            closePopupBtn.addEventListener("click", function() {
                reviewPopup.style.display = "none";
            });

            submitReviewBtn.addEventListener("click", function() {
                let rating = document.querySelector('input[name="star"]:checked');
                let userId = document.getElementById("userId").value.trim();
                let userPassword = document.getElementById("userPassword").value.trim();
                let reviewMessage = document.getElementById("reviewMessage").value.trim();

                if (!rating) {
                    showResponsePopup("❌ Please select a star rating!", false);
                    return;
                }

                if (userId === "" || userPassword === "") {
                    showResponsePopup("❌ Please enter your ID and Password!", false);
                    return;
                }

                if (reviewMessage === "") {
                    showResponsePopup("❌ Please write a review message!", false);
                    return;
                }

                fetch("php/submit_review.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `userId=${encodeURIComponent(userId)}&userPassword=${encodeURIComponent(userPassword)}&rating=${encodeURIComponent(rating.value)}&reviewMessage=${encodeURIComponent(reviewMessage)}`
                    })
                    .then(response => response.text()) // Log raw response first
                    .then(text => {
                        console.log("Raw Response:", text); // Debugging output
                        return JSON.parse(text); // Convert to JSON
                    })
                    .then(data => {
                        console.log("Parsed JSON:", data);
                        if (data.success) {
                            showResponsePopup("✅ Review submitted successfully!", true);
                            setTimeout(() => {
                                reviewPopup.style.display = "none";
                            }, 1500);
                        } else {
                            showResponsePopup(`❌ ${data.message}`, false);
                        }
                    })
                    .catch(error => {
                        console.error("Fetch Error:", error);
                        showResponsePopup("❌ Error submitting review. Try again!", false);
                    });
            });


            function showResponsePopup(message, isSuccess) {
                const popup = document.createElement("div");
                popup.classList.add("modern-popup");
                popup.innerHTML = `
        <div class="popup-content ${isSuccess ? 'success' : 'error'}">
            <span class="popup-icon">${isSuccess ? '✔️' : '❌'}</span>
            <p>${message}</p>
        </div>
    `;
                document.body.appendChild(popup);

                // Show animation
                setTimeout(() => {
                    popup.classList.add("show");
                }, 10);

                // Close after 3 seconds
                setTimeout(() => {
                    popup.classList.remove("show");
                    setTimeout(() => popup.remove(), 300);
                }, 3000);
            }
        });
    </script>
</body>


</html>