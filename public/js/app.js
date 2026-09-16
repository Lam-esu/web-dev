document.addEventListener('DOMContentLoaded', function () {

    // ---------------------------------------------------------
    // 1. Password Visibility Toggle & Match Checker (Merged)
    // ---------------------------------------------------------
    
    const toggleButtons = document.querySelectorAll('.toggle-password');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetSelector = this.getAttribute('data-target');
            const inputField = document.querySelector(targetSelector);
            
            if (!inputField) return;

            if (inputField.type === 'password') {
                inputField.type = 'text';
                this.textContent = 'Hide';
            } else {
                inputField.type = 'password';
                this.textContent = 'Show';
            }
        });
    });

    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const matchMessage = document.getElementById('password-match-message');

    if (passwordInput && confirmInput && matchMessage) {
        const checkMatch = function () {
            if (confirmInput.value === '') {
                matchMessage.textContent = '';
                return;
            }

            if (passwordInput.value === confirmInput.value) {
                matchMessage.textContent = 'Passwords match.';
                matchMessage.style.color = '#16a34a'; // Success green
            } else {
                matchMessage.textContent = 'Passwords do not match.';
                matchMessage.style.color = '#dc2626'; // Error red
            }
        };

        passwordInput.addEventListener('input', checkMatch);
        confirmInput.addEventListener('input', checkMatch);
    }

    // ---------------------------------------------------------
    // 2. Simple Navigation (Active States for Pills/Menus)
    // ---------------------------------------------------------
    
    // This allows users to click the filter "pills" (like All, Music, Podcasts) 
    // and visually changes the active one.
    const navPills = document.querySelectorAll('.pill');
    
    navPills.forEach(pill => {
        pill.addEventListener('click', function(e) {
            // Ignore the logout button pill so it can submit its form normally
            if(this.closest('form')) return; 

            // Find all sibling pills within the same container
            const siblings = this.parentElement.querySelectorAll('.pill');
            
            // Remove 'active' class from all siblings
            siblings.forEach(s => s.classList.remove('active'));
            
            // Add 'active' class to the clicked pill
            this.classList.add('active');
        });
    });

    // ---------------------------------------------------------
    // 3. Profile Click to Log Out
    // ---------------------------------------------------------
    
    // Select the mini profile avatar in the top navigation
    const profileAvatar = document.querySelector('.avatar-circle-mini');
    
    if (profileAvatar) {
        // Add a pointer cursor so the user knows it is clickable
        profileAvatar.style.cursor = 'pointer';
        
        profileAvatar.addEventListener('click', function() {
            // Optional: Ask for confirmation before logging out
            const wantsToLogout = confirm('Are you sure you want to log out of Spotibai?');
            
            if (wantsToLogout) {
                // Find your hidden Laravel logout form and submit it programmatically
                const logoutForm = document.querySelector('form[action*="logout"]');
                if (logoutForm) {
                    logoutForm.submit();
                } else {
                    console.error("Logout form not found on this page.");
                }
            }
        });
    }
});