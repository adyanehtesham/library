"use strict";

// This block will run when the DOM is loaded (once elements exist), it's really only necessary as a IE 9 fallback for lack of support for defer
window.addEventListener("DOMContentLoaded", () => {
    const nav = document.getElementById("nav");
    const navButton = document.getElementById("nav-toggle");
    let hidden = true;
    
    nav.classList.add("hidden");

    navButton.addEventListener("click", () => {
        if (hidden) {
            nav.classList.remove("hidden");
            hidden = false;
        } else {
            nav.classList.add("hidden");
            hidden = true;
        }
    });
    
});