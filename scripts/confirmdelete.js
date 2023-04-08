// function to confirm deletion of a book
// displays the confirmation message and delets book if the user confirms
// redirects back to index.php in the end
const confirmDelete = (bookId) => {
    if (confirm("Are you sure you want to delete this book?")) {
        window.location = "deletebook.php?id=" + bookId;
    }
}

