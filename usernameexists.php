<?php

// php script to check if a username already exists in the database
// returns true if book exists, false if it does no

include 'includes/library.php';
$pdo = connectDB();

$username = $_GET['username'] ?? null;
if (!$username) {
    echo 'error';
    exit();
}

$query = "SELECT * from library_users where username=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username]);
$result = $stmt->fetch();
if ($result) {
    echo 'true';
} else {
    echo 'false';
}
exit();
?>