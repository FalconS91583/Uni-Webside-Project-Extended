// Poprawiony SkryptF.js
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registration-form');

    // Helper function to show error messages
    function showError(input, message) {
        const errorElement = input.nextElementSibling;
        if (errorElement) {
            errorElement.textContent = message;
            input.classList.add('error');
        }
    }

    // Helper function to clear error messages
    function clearError(input) {
        const errorElement = input.nextElementSibling;
        if (errorElement) {
            errorElement.textContent = '';
            input.classList.remove('error');
        }
    }

    // Validation rules
    const validateField = {
        firstName: (value) => /^[a-zA-Z]{2,50}$/.test(value) || "Imię powinno zawierać od 2 do 50 liter.",
        lastName: (value) => /^[a-zA-Z]{2,50}$/.test(value) || "Nazwisko powinno zawierać od 2 do 50 liter.",
        login: (value) => /^[a-zA-Z0-9]{5,20}$/.test(value) || "Login powinien zawierać od 5 do 20 znaków alfanumerycznych.",
        email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || "Podaj poprawny adres email.",
        password: (value) => value.length >= 8 || "Hasło powinno mieć co najmniej 8 znaków.",
        confirmPassword: (value, form) => value === form.password.value || "Hasła nie są zgodne.",
        birthdate: (value) => Boolean(Date.parse(value)) || "Podaj poprawną datę urodzenia.",
        gender: (value) => !!value || "Wybierz płeć.",
        address: (value) => value.trim().length > 0 || "Adres nie może być pusty.",
        city: (value) => /^[a-zA-Z\s]{2,50}$/.test(value) || "Miasto powinno zawierać tylko litery.",
        postalCode: (value) => /^\d{2}-\d{3}$/.test(value) || "Kod pocztowy powinien być w formacie 11-111.",
        specialization: () => true // Specialization does not require validation
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let valid = true;

        Array.from(form.elements).forEach((input) => {
            if (input.name && validateField[input.name]) {
                const result = validateField[input.name](input.value, form);
                if (result !== true) {
                    showError(input, result);
                    valid = false;
                } else {
                    clearError(input);
                }
            }
        });

        if (valid) {
            form.submit(); // Submit the form if all validations pass
        }
    });

    // Real-time validation
    Array.from(form.elements).forEach((input) => {
        if (input.name && validateField[input.name]) {
            input.addEventListener('input', () => {
                const result = validateField[input.name](input.value, form);
                if (result === true) {
                    clearError(input);
                } else {
                    showError(input, result);
                }
            });
        }
    });

    // Fix radio button behavior for country selection
    const countryRadios = document.getElementsByName('country');
    countryRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            countryRadios.forEach(r => {
                const label = r.nextElementSibling;
                if (label) {
                    label.style.color = r.checked ? '#000' : '#aaa';
                }
            });

            // Trigger validation for fields below country
            Array.from(form.elements).forEach((input) => {
                if (input.name && validateField[input.name]) {
                    const result = validateField[input.name](input.value, form);
                    if (result === true) {
                        clearError(input);
                    } else {
                        showError(input, result);
                    }
                }
            });
        });
    });
});
