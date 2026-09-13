

document.addEventListener('DOMContentLoaded', function () {


    document.querySelectorAll('.toggle-password').forEach(function (button) {
        button.addEventListener('click', function () {
            var targetSelector = button.getAttribute('data-target');
            var input = document.querySelector(targetSelector);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = 'Hide';
            } else {
                input.type = 'password';
                button.textContent = 'Show';
            }
        });
    });


    var passwordInput = document.getElementById('password');
    var confirmInput = document.getElementById('password_confirmation');
    var matchMessage = document.getElementById('password-match-message');

    if (passwordInput && confirmInput && matchMessage) {
        var checkMatch = function () {
            if (confirmInput.value === '') {
                matchMessage.textContent = '';
                return;
            }

            if (passwordInput.value === confirmInput.value) {
                matchMessage.textContent = 'Passwords match.';
                matchMessage.style.color = '#16a34a';
            } else {
                matchMessage.textContent = 'Passwords do not match.';
                matchMessage.style.color = '#dc2626';
            }
        };

        passwordInput.addEventListener('input', checkMatch);
        confirmInput.addEventListener('input', checkMatch);
    }
});
