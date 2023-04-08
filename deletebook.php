<?php

session_start();

include('includes/library.php');
$pdo = connectDB();

// delete the book
$query = "DELETE FROM library_books WHERE book_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_GET['id']]);

// redirect to the index page
header('Location: index.php');
exit();

?>