<?php

include 'includes/library.php';
$pdo = connectDB();

session_start();

$id = $_SESSION['id'];
$isbn = $_GET['isbn'];

// query to check if isbn already exists for current user
$query = "SELECT * FROM `library_books` WHERE `isbn` = ? AND `id` = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$isbn, $id]);
$book = $stmt->fetch();
if ($book) {
    echo 'true';
} else {
    echo 'false';
}
?>