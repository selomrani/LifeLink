let showPassword = document.getElementById('togglePassword');

let passwordInputs = document.querySelectorAll('.pass-input'); 

let icon = document.getElementById('icon');

passwordInputs.forEach(passwordInput => {
    showPassword.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
        else if (passwordInput.type === 'text') {
            passwordInput.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
});