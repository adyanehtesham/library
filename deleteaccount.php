<?php
// start the session
session_start();

include('includes/library.php');
$pdo = connectDB();

// delete the user's books
$query = "DELETE FROM library_books WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['id']]);

// delete the user's account
$query = "DELETE FROM library_users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['id']]);

// destroy the session
session_destroy();
// redirect to the index page
header('Location: index.php');
exit();

?>