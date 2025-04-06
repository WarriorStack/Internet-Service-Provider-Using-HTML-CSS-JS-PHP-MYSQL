// Get all sidebar links
const sidebarLinks = document.querySelectorAll('.sidebar a');

// Add click event listener to each link
sidebarLinks.forEach(link => {
    link.addEventListener('click', (event) => {
        // Prevent default behavior of links
        event.preventDefault();

        // Remove 'active' class from all links
        sidebarLinks.forEach(item => item.classList.remove('active'));

        // Add 'active' class to the clicked link
        link.classList.add('active');

        // Navigate to specific pages based on the link text
        const linkText = link.querySelector('h3').textContent.trim();

        if (linkText === 'Dashboard') {
            window.location.href = 'dashboard.php';
        } else if (linkText === 'Profile') {
            window.location.href = 'profile.php';
        }else if (linkText === 'Plans') {
            window.location.href = 'plan.php';
        }else if(linkText === 'Change Plan / Recharge'){
            window.location.href = "updatePlan.php"
        }else if(linkText === 'Complaints'){
            window.location.href = "complain.php"
        }else if (linkText === 'Logout') {
            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to logout?");
            
            // If the user clicks 'OK' (true), proceed with the redirection
            if (userConfirmed) {
                window.location.href = "http://localhost/ISPProject/loginPage.php";
            }
            // If the user clicks 'Cancel' (false), do nothing
            else {
                // Optionally, you can handle the cancel case here (e.g., display a message)
                console.log("Logout canceled.");
            }
        }
        
    });
});
