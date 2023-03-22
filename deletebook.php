<?php

session_start();

include('includes/library.php');
$pdo = connectDB();

$query = "DELETE FROM library_books WHERE book_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_GET['id']]);

header('Location: index.php');
exit();

?>