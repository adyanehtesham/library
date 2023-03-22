<?php

$book_id = $_GET['id'];

include 'includes/library.php';
$pdo = connectDB();

// fetch book details
$query = "SELECT * from library_books where book_id=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$book_id]);
$result = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<!-- Setting up the page's metadata -->

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Importing Stylesheet -->
    <link rel="stylesheet" href="styles/main.css">
    <!-- Title that's displayed on browser tab -->
    <title>Bata - Book Details</title>
</head>

<!-- body consists of header, main body and footer -->

<body>
    <!-- Header consists of website title and logo and a navbar with links to various pages -->
    <?php include "includes/header.php" ?>

    <!-- Main section of the page that displays the details of a book -->
    <main>
        <!-- Section that displays the details -->
        <section class="bookDetails">
            <h2 class="bookTitle">
                <?= $result['title'] ?>
            </h2>
            <div class="bookCover">
                <img src=<?= "../../.." . $result['book_cover'] ?> alt="book cover" />
            </div>
            <div class="bookAuthor">
                <h3>Author:
                    <?= $result['author'] ?>
                </h3>
            </div>
            <div class="bookRating">
                <h3>Rating:
                    <?= str_repeat("*", $result['rating']) ?>
                </h3>
            </div>
            <div class="bookGenre">
                <h3>Genre:
                    <?= $result['genre'] ?>
                </h3>
            </div>
            <div class="bookPublish">
                <h3>Publication Date:
                    <?= $result['pub_date'] ?>
                </h3>
            </div>
            <div class="bookISBN">
                <h3>ISBN:
                    <?= $result['isbn'] ?>
                </h3>
            </div>
            <div class="bookFormat">
                <h3>Book Format:
                    <?= $result['book_format'] ?>
                </h3>
            </div>
            <div class="bookFile">
                <h3>Ebook File:</h3>
                <a href=<?= "../../.." . $result['book_file'] ?>><?= $result['title'] ?></a>
            </div>
            <div class="bookDescription">
                <h3>Description:</h3>
                <p>
                    <?= $result['description'] ?>
                </p>
            </div>
        </section>
    </main>
    <!-- Footer has my name and where I got the design inspiration from -->
    <? include "includes/footer.php" ?>

</body>

</html>