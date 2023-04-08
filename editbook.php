<?php

session_start();

include 'includes/library.php';
$pdo = connectDB(); //connect to database

// set variables
$current = null;
$book_id = $_GET['id'] ?? $_POST['book_id'];


// fetch book details
$query = "SELECT * from library_books where book_id=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$book_id]);
$current = $stmt->fetch();

// set variables
$id = $_SESSION['id'] ?? null;
$title = $_POST['title'] ?? $current['title'];
$author = $_POST['author'] ?? $current['author'];
$rating = $_POST['rating'] ?? $current['rating'];
$genre = $_POST['genre'] ?? $current['genre'];
$pub_date = $_POST['pub_date'] ?? $current['pub_date'];
$isbn = $_POST['isbn'] ?? $current['isbn'];
$book_format = $_POST['book_format'] ?? $current['book_format'];
$book_file = $_FILES['book_file']['name'] ?? $current['book_file'];
$description = $_POST['description'] ?? $current['description'];
$cover_image = $_FILES['cover_image']['name'] ?? $current['book_cover'];

$errors = array();


// create new file name based on user id and isb
function createFilename($file, $path, $prefix, $uniqueID)
{
    $filename = $_FILES[$file]['name'];
    $exts = explode(".", $filename);
    $ext = $exts[count($exts) - 1];
    $filename = $prefix . $uniqueID . "." . $ext;
    $newname = $path . $filename;
    return $newname;
}

// check for errors when uploading a file, taken from course notes
function checkErrors($file, $limit)
{
    //modified from http://www.php.net/manual/en/features.file-upload.php
    try {
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($_FILES[$file]['error']) || is_array($_FILES[$file]['error'])) {
            throw new RuntimeException('Invalid parameters.');
        }

        // Check Error value.
        switch ($_FILES[$file]['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here.
        if ($_FILES[$file]['size'] > $limit) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // Check the File type
        if (
            $file == 'cover_image'
            and exif_imagetype($_FILES[$file]['tmp_name']) != IMAGETYPE_GIF
            and exif_imagetype($_FILES[$file]['tmp_name']) != IMAGETYPE_JPEG
            and exif_imagetype($_FILES[$file]['tmp_name']) != IMAGETYPE_PNG
        ) {

            throw new RuntimeException('Invalid file format.');
        }

        return "";

    } catch (RuntimeException $e) {

        return $e->getMessage();

    }

}

// upload file to server, create new name based on user id and isbn
// checks for errors when uploading file and moving file to www_data
function upload_file($file, $isbn, $errors)
{
    $uniqueID = '_user' . $_SESSION['id'] . '_' . $isbn; //use database autonumber for unique value?
    $path = '../../../www_data/'; //location file should go, relative to addbook


    if (is_uploaded_file($_FILES[$file]['tmp_name'])) {

        $results = checkErrors($file, 1024000);
        if (strlen($results) > 0) {
            echo $results; //this should be handled more gracefully
        } else {
            $newname = createFilename($file, $path, $file, $uniqueID);
            if (!move_uploaded_file($_FILES[$file]['tmp_name'], $newname)) {
                echo "Failed to move uploaded file."; //this should be handled more gracefully
                $errors['file'] = true;
            }
            return createFilename($file, '/www_data/', $file, $uniqueID);
        }
    } else {
        $results = checkErrors('cover_image', 1024000);
        echo $results;
    }
}

// if submit button is clicked, check for errors and upload files
if (isset($_POST['submit'])) {

    // check for errors
    // did not add error messages for if fields are empty, html already does this
    if (empty($title)) {
        $errors['title'] = true;
    }
    if (empty($author)) {
        $errors['author'] = true;
    }
    if (empty($rating)) {
        $errors['rating'] = true;
    }
    if (empty($genre)) {
        $errors['genre'] = true;
    }
    if (empty($pub_date)) {
        $errors['pub_date'] = true;
    }

    if ($isbn != $current['isbn']) {

        // query to check if isbn already exists for current user
        $query = "SELECT * FROM `library_books` WHERE `isbn` = ? AND `id` = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$isbn, $id]);
        $book = $stmt->fetch();
        if ($book) {
            $errors['isbn_exists'] = true;
        }
    }
    if (!isset($isbn) || strlen($isbn) != 13 || !is_numeric($isbn)) {
        $errors['isbn'] = true;
    }
    // image editing does not work, no time
    // if (empty($cover_image)) {
    //     $errors['file'] = true;
    // }

    var_dump($errors);
    if (count($errors) === 0) {
        // upload files
        $cover_image = upload_file('cover_image', $isbn, $errors);
        $book_file = upload_file('book_file', $isbn, $errors);
        // var_dump($book_file);

        // get book format extension
        $period = explode(".", $_FILES['book_file']['name']);
        $book_format = end($period);

        // remove html tags from description
        $description = strip_tags($description);

        // insert book into database
        $query = "UPDATE `library_books` SET `title` = ?, `author` = ?, `rating` = ?, `genre` = ?, `pub_date` = ?, `isbn` = ?, `book_format` = ?, `book_file` = ?, `description` = ?, `book_cover` = ? WHERE `library_books`.`book_id` = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$title, $author, $rating, $genre, $pub_date, $isbn, $book_format, $book_file ?? $current['book_file'], $description, $cover_image ?? $current['book_cover'], $book_id]);

    }


}

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
    <title>Bata - Edit Book</title>
</head>

<!-- body consists of header, main body and footer -->

<body>
    <!-- Header consists of website title and logo and a navbar with links to various pages -->
    <?php include "includes/header.php" ?>

    <!-- Main section of the page that has the form where user enters all the necessary details of a book -->
    <main>
        <section class="addBookSection">
            <h2>Edit
                <?= $title ?>
            </h2>
            <!-- The form, a longer one but pretty self explanatory -->
            <form enctype="multipart/form-data" id="addBookForm" name="addBook"
                action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <input type="hidden" name="book_id" id="book_id" value=<?= $book_id ?> />
                <div class="bookFileInput">
                    <!-- Realized this is better left for javascript with an event listener -->
                    <div class="coverPlaceholder">
                        <img src=<?= "../../../" . $cover_image ?> />
                    </div>
                    <div>
                        <label for="cover_image">Cover Image:</label>
                        <input type="file" name="cover_image" id="cover_image" value=<?= "../../.." . $cover_image ?> />
                    </div>
                    <div>
                        <label for="book_file">Book File:</label>
                        <input type="file" name="book_file" id="book_file" value=<?= "../../.." . $book_file ?> />
                    </div>
                </div>
                <div class="bookDetailInput">
                    <div>
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" placeholder="Title" value=<?= $title ?> required />
                    </div>
                    <div>
                        <label for="Author">Author:</label>
                        <input type="text" name="author" id="author" placeholder="John Doe" value=<?= $author ?>
                            required />
                    </div>
                    <div>
                        <label for="rating">Rating:</label>
                        <input type="range" name="rating" id="rating" min="0" max="5" value=<?= $rating ?> />
                    </div>
                    <div>
                        <label for="genre">Genre:</label>
                        <select name="genre" id="genre">
                            <option value="fiction" <?php if ($genre === "fiction")
                                echo "selected" ?>>Fiction
                                </option>
                                <option value="non-fiction" <?php if ($genre === "non-fiction")
                                echo "selected" ?>>Non-Fiction</option>
                                <option value="other" <?php if ($genre === "other")
                                echo "selected" ?>>Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="pub_date">Publication Date:</label>
                            <input type="date" name="pub_date" id="pub_date" value=<?= $pub_date ?> required />
                    </div>
                    <div>
                        <label for="ISBN">ISBN:</label>
                        <input type="text" name="isbn" id="isbn" value=<?= $isbn ?> required />
                    </div>
                    <span class="<?= !isset($errors['isbn']) ? 'hidden' : ""; ?>">* ISBN not entered correctly</span>
                    <span class="<?= !isset($errors['isbn_exists']) ? 'hidden' : ""; ?>">* The book is already in your
                        account</span>
                    <div class="description">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" rows="10" cols="50"><?= $description ?></textarea>
                    </div>
                </div>
                <div class="submitBook">
                    <button class="button" type="submit" name="submit" id="submit">
                        Edit
                    </button>
                </div>
            </form>
        </section>
    </main>
    <!-- Footer has my name and where I got the design inspiration from -->
    <?php include "includes/footer.php" ?>

</body>

</html>