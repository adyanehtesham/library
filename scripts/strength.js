"use strict";

const check_password_length = (password) => {
    return password.length >= 8;
}

const check_password_numbers = (password) => {
    return /\d/.test(password);
}

const check_password_special = (password) => {
    return /[!@#$%^&*()]/.test(password);
}

const check_password_uppercase = (password) => {
    return /[A-Z]/.test(password);
}

const check_password_lowercase = (password) => {
    return /[a-z]/.test(password);
}

// This block will run when the DOM is loaded (once elements exist), it's really only necessary as a IE 9 fallback for lack of support for defer
window.addEventListener("DOMContentLoaded", () => {

    const passwordInput = document.getElementById("password");
    const strengthText = document.getElementById("strength-text");

    document.addEventListener("submit", (e) => {
        // passwordInput.addEventListener("input", () => {
        const strength = [check_password_length(passwordInput.value), check_password_numbers(passwordInput.value), check_password_special(passwordInput.value), check_password_uppercase(passwordInput.value), check_password_lowercase(passwordInput.value)];
        let strengthNum = 0;
        strength.map((val) => {
            if (val) {
                strengthNum++;
            }
        })
        strengthText.innerHTML = `Password Strength: ${strengthNum}/5`;
        if (strengthNum < 5) {
            e.preventDefault();
            console.log(strengthNum);
            passwordInput.parentElement.nextElementSibling.classList.remove("hidden");
        } else {
            console.log(strengthNum);
            passwordInput.parentElement.nextElementSibling.classList.add("hidden");
        }
        // });
    })


});