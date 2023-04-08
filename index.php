<?php
/****************************************
// ENSURES THE USER HAS ACTUALLY LOGGED IN
// IF NOT REDIRECT TO THE LOGIN PAGE HERE
******************************************/
session_start();
if (!(isset($_SESSION['username']))) {
    header("Location: login.php");
    exit();
}

// include library
include 'includes/library.php';
$pdo = connectDB();

$stmt = $pdo->prepare("SELECT * FROM `library_books` WHERE `id` = ?");
$stmt->execute([$_SESSION['id']]);
$books = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<!-- Setting up the page's metadata -->

<head>
    <?php
    $page_title = "Book Catalogue";
    include "includes/metadata.php"
        ?>
    <script defer src="scripts/confirmdelete.js"></script>
    <script defer src="scripts/details.js"></script>
</head>

<!-- body consists of header, main body and footer -->

<body>
    <!-- Header consists of website title and logo and a navbar with links to various pages -->
    <?php include "includes/header.php" ?>

    <!-- Main section of the page that holds the catalogue of all books in user's library -->
    <main>
        <section>
            <h2>Catalogue</h2>
            <!-- Link to add another book -->
            <div class="addBookButton">
                <a class="button" href="./addbook.php"><img src="./images/icons/add.svg" alt="Add Book Icon"
                        class="icon" />Add a book</a>
            </div>
            <!-- The actual catalogue div that holds all the books, each book is in its own div -->
            <div class="catalogue">
                <?php foreach ($books as $book): ?>
                    <div class="book">
                        <img src=<?= "../../.." . $book['book_cover'] ?> alt="Intro To Algorithms Cover" width="150"
                            height="180" />
                        <p>
                            <?= $book['title'] ?>
                        </p>
                        <p>
                            <?= $book['author'] ?>
                        </p>
                        <div class="bookOptions">
                            <a class="button" href=<?= "./editbook.php?id=" . $book['book_id'] ?>>Edit</a>
                            <a class="button" onclick="displayDetails(<?= $book['book_id'] ?>)">Details</a>
                            <a id="deleteButton" class="button" onclick="confirmDelete(<?= $book['book_id'] ?>)">Delete</a>
                        </div>
                    </div>
                    <!-- The Modal -->
                    <div id="<?= 'modal' . $book['book_id'] ?>" class="modal">
                        <div class="modalcontents">
                            <!-- Section that displays the details -->
                            <span class="close">&times;</span>
                            <section class="bookDetails">
                                <h2 class="bookTitle">
                                    <?= $book['title'] ?>
                                </h2>
                                <div class="bookCover">
                                    <img src=<?= "../../.." . $book['book_cover'] ?> alt="book cover" />
                                </div>
                                <div class="bookAuthor">
                                    <h3>Author:
                                        <?= $book['author'] ?>
                                    </h3>
                                </div>
                                <div class="bookRating">
                                    <h3>Rating:
                                        <?= str_repeat("*", $book['rating']) ?>
                                    </h3>
                                </div>
                                <div class="bookGenre">
                                    <h3>Genre:
                                        <?= $book['genre'] ?>
                                    </h3>
                                </div>
                                <div class="bookPublish">
                                    <h3>Publication Date:
                                        <?= $book['pub_date'] ?>
                                    </h3>
                                </div>
                                <div class="bookISBN">
                                    <h3>ISBN:
                                        <?= $book['isbn'] ?>
                                    </h3>
                                </div>
                                <div class="bookFormat">
                                    <h3>Book Format:
                                        <?= $book['book_format'] ?>
                                    </h3>
                                </div>
                                <div class="bookFile">
                                    <h3>Ebook File:</h3>
                                    <a href=<?= "../../.." . $book['book_file'] ?>><?= $book['title'] ?></a>
                                </div>
                                <div class="bookDescription">
                                    <h3>Description:</h3>
                                    <p>
                                        <?= $book['description'] ?>
                                    </p>
                                </div>
                            </section>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <!-- Footer has my name and where I got the design inspiration from -->
    <?php include "includes/footer.php" ?>
</body>

</html>