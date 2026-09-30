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

    // Password Strength Analyzer
    const pwdInput = document.getElementById('password');
    
    if (pwdInput) {
        pwdInput.addEventListener('input', function() {
            const val = this.value;
            
            // Define rules
            const reqs = {
                length: val.length >= 8,
                upper: /[A-Z]/.test(val),
                lower: /[a-z]/.test(val),
                number: /[0-9]/.test(val),
                special: /[^A-Za-z0-9]/.test(val)
            };

            let passedCount = 0;

            // Toggle HTML classes for list items
            for (const [key, passed] of Object.entries(reqs)) {
                const el = document.getElementById('req-' + key);
                if (el) {
                    if (passed) {
                        el.classList.remove('invalid');
                        el.classList.add('valid');
                        passedCount++;
                    } else {
                        el.classList.remove('valid');
                        el.classList.add('invalid');
                    }
                }
            }

            // Update Progress Bar & Text
            const bar = document.getElementById('strength-bar');
            const text = document.getElementById('strength-text');
            
            if (bar && text) {
                if (passedCount === 0) {
                    bar.style.width = '0%';
                    text.textContent = 'Password strength: None';
                    text.style.color = '#dc2626';
                } else if (passedCount <= 2) {
                    bar.style.width = '33%';
                    bar.style.backgroundColor = '#dc2626'; // Red
                    text.textContent = 'Password strength: Weak';
                    text.style.color = '#dc2626';
                } else if (passedCount <= 4) {
                    bar.style.width = '66%';
                    bar.style.backgroundColor = '#f59e0b'; // Orange
                    text.textContent = 'Password strength: Fair';
                    text.style.color = '#f59e0b';
                } else {
                    bar.style.width = '100%';
                    bar.style.backgroundColor = '#16a34a'; // Green
                    text.textContent = 'Password strength: Strong';
                    text.style.color = '#16a34a';
                }
            }
        });
    }
});