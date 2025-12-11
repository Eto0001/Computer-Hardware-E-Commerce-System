document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");

    const patterns = {
        email: /^\S+@\S+\.\S+$/,
        password: /^.{1,}$/ // at least 1 char
    };

    function showError(id, message) {
        document.getElementById(id).textContent = message;
    }

    // Real-time validation
    document.getElementById("email").addEventListener("input", function () {
        showError("emailError", patterns.email.test(this.value.trim()) ? "" : "Invalid email");
    });

    document.getElementById("password").addEventListener("input", function () {
        showError("passwordError", patterns.password.test(this.value.trim()) ? "" : "Enter password");
    });

    // Submit validation
    form.addEventListener("submit", function (e) {
        let hasError = false;

        const emailValue = document.getElementById("email").value.trim();
        const passValue = document.getElementById("password").value.trim();

        if (!patterns.email.test(emailValue)) {
            showError("emailError", "Invalid email");
            hasError = true;
        }

        if (!patterns.password.test(passValue)) {
            showError("passwordError", "Enter password");
            hasError = true;
        }

        if (hasError) e.preventDefault();
    });
});
