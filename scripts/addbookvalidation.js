// set up nodes for form elements
const addBookForm = document.getElementById("addBookForm");
const title = document.getElementById("title");
const author = document.getElementById("author");
const isbn = document.getElementById("isbn");
const isbnexists = document.getElementById("isbn_exists");
const isbnlength = document.getElementById("isbn_length");
const genre = document.getElementById("genre");
const date = document.getElementById("pub_date");
const coverimage = document.getElementById("cover_image");
const bookfile = document.getElementById("book_file");

// create a request object
let request = new XMLHttpRequest();
// set up a callback function for the request object
request.addEventListener('load', (ev) => {
    if (request.status != 200) {
        console.log(request);
    }
    else if (request.response == "true") {
        // if the isbn already exists, show the error message
        isbnexists.classList.remove("hidden");
    } else {
        // if the isbn does not exist, hide the error message
        isbnexists.classList.add("hidden");
    }
});

// function that removes the hidden class from the error message
showErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.remove("hidden");
}

// function that adds the hidden class to the error message
hideErrorMessage = (element) => {
    element.parentElement.nextElementSibling.classList.add("hidden");
}

// add an event listener to the form
addBookForm.addEventListener("submit", (e) => {

    // check if the isbn already exists in the database
    request.open("GET", "./bookexists.php?isbn=" + isbn.value);
    // send the request
    request.send();

    let errors = 0; // variable to keep track of errors

    // check if the fields are empty
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
    } else if (isbn.value.length != 13) { // check if the isbn is 13 characters long
        errors++;
        isbnlength.classList.remove("hidden");
    } else {
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

    if (coverimage.value === "" || coverimage.value == null) {
        errors++;
        showErrorMessage(coverimage)
    } else hideErrorMessage(coverimage)

    if (bookfile.value === "" || bookfile.value == null) {
        errors++;
        showErrorMessage(bookfile)
    } else hideErrorMessage(bookfile)

    console.log(errors)

    // if there are errors, prevent the form from submitting
    if (errors > 0) {
        e.preventDefault();
    }
})