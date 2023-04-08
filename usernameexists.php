<?php

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