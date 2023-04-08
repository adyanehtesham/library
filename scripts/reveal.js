"use strict";

// This block will run when the DOM is loaded (once elements exist), it's really only necessary as a IE 9 fallback for lack of support for defer
document.addEventListener("DOMContentLoaded", () => {

    const passwordInput = document.getElementById("password");
    const revealButton = document.getElementById("reveal");

    revealButton.addEventListener("click", () => {
        if (passwordInput.type === "text") {
            passwordInput.type = "password";
            passwordInput.nextElementSibling.innerHTML = "Show";
        } else {
            passwordInput.type = "text";
            passwordInput.nextElementSibling.innerHTML = "Hide";
        }
    })

    
})