// Helper function to show errors
function showError(id, message) {
    document.getElementById(id).textContent = message;
}

document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("registerForm");

    const fields = {
        name: /^[A-Z][a-zA-Z\s]{1,30}$/,
        email: /^\S+@\S+\.\S+$/,
        phone: /^(98|97|96)\d{8}$/,
        address: /.+/,
        password: /^.{6,}$/
    };

    // Real-time validation
    Object.keys(fields).forEach(field => {
        document.getElementById(field).addEventListener("input", function () {
            let value = this.value.trim();
            let valid = fields[field].test(value);

            if (!valid) {
                showError(field + "Error", "Invalid " + field);
            } else {
                showError(field + "Error", "");
            }
        });
    });

    // Submit Validation
    form.addEventListener("submit", function (event) {
        let hasError = false;

        Object.keys(fields).forEach(field => {
            let value = document.getElementById(field).value.trim();
            let valid = fields[field].test(value);

            if (!valid) {
                showError(field + "Error", "Invalid " + field);
                hasError = true;
            }
        });

        if (hasError) {
            event.preventDefault();
        }
    });

});
