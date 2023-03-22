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
                            <a class="button" href=<?= "./deletebook.php?id=" . $book['book_id'] ?>>Delete</a>
                            <a class="button" href=<?= "./details.php?id=" . $book['book_id'] ?>>Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
    <!-- Footer has my name and where I got the design inspiration from -->
    <? include "includes/footer.php" ?>
</body>

</html>