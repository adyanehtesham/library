const registerForm = document.getElementById("register_form");
const username = document.getElementById("username");
const name = document.getElementById("name");
const email = document.getElementById("email");
const password = document.getElementById("password");
const verifyPassword = document.getElementById("VerifyPassword");
const errorElement = document.getElementById("error");

let request = new XMLHttpRequest();
request.addEventListener('load', (ev) => {
    if (request.status != 200) {
        console.log(request);
    }
    else if (request.response == "true") {
        if (document.getElementById("usernameexists") == null) {
            username.parentElement.nextElementSibling.insertAdjacentHTML("afterend", "<p id='usernameexists' class='error'>*That user already exists</p>");
        }
    } else {
        if (document.getElementById("usernameexists") != null) {
            document.getElementById("usernameexists").remove();
        }
    }
});

showErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.remove("hidden");
}

hideErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.add("hidden");
}

registerForm.addEventListener("submit", (e) => {

    request.open("GET", "./usernameexists.php?username=" + username.value);
    request.send();

    let errors = 0;
    if (username.value === "" || username.value == null) {
        errors++;
        showErrorMessage(username)
    } else hideErrorMessage(username)
    if (name.value === "" || name.value == null) {
        errors++;

        showErrorMessage(name)
    } else hideErrorMessage(name)

    if (email.value === "" || email.value == null) {
        errors++;
        showErrorMessage(email)

    } else hideErrorMessage(email)

    if (password.value === "" || password.value == null) {
        errors++;
        showErrorMessage(password)
    } else hideErrorMessage(password)

    if (verifyPassword.value === "" || verifyPassword.value == null || password.value !== verifyPassword.value) {
        errors++;
        showErrorMessage(verifyPassword)
    } else hideErrorMessage(verifyPassword)

    if (password.value !== verifyPassword.value) {
        errors++;
        showErrorMessage(verifyPassword)

    } else hideErrorMessage(verifyPassword)

    if (errors > 0) {
        e.preventDefault();
    }
})