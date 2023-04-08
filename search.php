<?php

session_start();
$search = $_POST['search'] ?? null;
$errors = [];

include 'includes/library.php';

$pdo = connectDB();

// get all books
$stmt = $pdo->prepare("SELECT * FROM `library_books` WHERE `id` = ?");
$stmt->execute([$_SESSION['id']]);
$books = $stmt->fetchAll();

if (isset($_POST['submit'])) {

    // search for books

    $query = "SELECT * from library_books where title like ? and id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['%' . $search . '%', $_SESSION['id']]);
    $books = $stmt->fetchAll();
    if (!$books) {
        $errors['user'] = true;
    } else {
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<!-- Setting up the page's metadata -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Importing Stylesheet -->
    <link rel="stylesheet" href="styles/main.css">
    <!-- Title that's displayed on browser tab -->
    <title>Bata - Login</title>
</head>

<!-- body consists of header, main body and footer -->

<body>
    <!-- Header consists of website title and logo and a navbar with links to various pages -->
    <?php include "includes/header.php" ?>

    <!-- Main section of the page that has the search form and the results as well -->
    <main>
        <section>
            <h2>Search</h2>
            <!-- search form, a text input and a go button -->
            <form class="formFilter" id="search_form" name="search_form"
                action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <input type="text" name="search" id="search" placeholder="Search" value="<?= $search ?>" required />
                <button class="button" type="submit" name="submit" id="submit">
                    Go
                </button>
            </form>
            <!-- Search results, same way main catalogue is styled, that's why same class names -->
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
                            <a class="button" href="./editbook.php">Edit</a>
                            <a class="button" href="./deletebook.php">Delete</a>
                            <a class="button" href="./details.php">Details</a>
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