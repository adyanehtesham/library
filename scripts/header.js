// get the nav element
const nav = document.getElementById("nav");
// get the nav toggle button
const navButton = document.getElementById("nav-toggle");

// variable to keep track of the state of the nav
let hidden = true;

// hide the nav
nav.classList.add("hidden");

// add an event listener to the nav toggle button
navButton.addEventListener("click", () => {
    if (hidden) {
        // if the nav is hidden, show it
        nav.classList.remove("hidden");
        hidden = false;
    } else {
        // if the nav is shown, hide it
        nav.classList.add("hidden");
        hidden = true;
    }
});