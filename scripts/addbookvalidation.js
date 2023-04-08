const addBookForm = document.getElementById("addBookForm");
const title = document.getElementById("title");
const author = document.getElementById("author");
const isbn = document.getElementById("isbn");
const isbnexists = document.getElementById("isbn_exists");
const isbnlength = document.getElementById("isbn_length");
const genre = document.getElementById("genre");
const date = document.getElementById("pub_date");

let request = new XMLHttpRequest();
request.addEventListener('load', (ev) => {
    if (request.status != 200) {
        console.log(request);
    }
    else if (request.response == "true") {
        isbnexists.classList.remove("hidden");
    } else {
        isbnexists.classList.add("hidden");
    }
});

showErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.remove("hidden");
}

hideErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.add("hidden");
}

addBookForm.addEventListener("submit", (e) => {

    request.open("GET", "./bookexists.php?isbn=" + isbn.value);
    request.send();

    let errors = 0;
    if (title.value === "" || title.value == null) {
        errors++;
        showErrorMessage(title)
    } else hideErrorMessage(title)

    if (author.value === "" || author.value == null) {
        errors++;

        showErrorMessage(author)
    } else hideErrorMessage(author)

    if (isbn.value === "" || isbn.value == null) {
        errors++;
        showErrorMessage(isbn)

    } else if (isbn.value.length != 13) {
        errors++;
        isbnlength.classList.remove("hidden");
    } else {
        errors++;
        hideErrorMessage(isbn)

        isbnlength.classList.add("hidden");
    }

    if (genre.value === "" || genre.value == null) {
        errors++;
        showErrorMessage(genre)
    } else hideErrorMessage(genre)

    if (date.value === "" || date.value == null) {
        errors++;
        showErrorMessage(date)
    } else hideErrorMessage(date)

    if (errors > 0) {
        e.preventDefault();
    }
})