<?php

session_start();

include('includes/library.php');
$pdo = connectDB();

$query = "DELETE FROM library_books WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['id']]);

$query = "DELETE FROM library_users WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['id']]);

session_destroy();
header('Location: index.php');
exit();

?>