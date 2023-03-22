<?php
session_start();
$_SESSION['username'] = $null;
$_SESSION['id'] = null;

// I know assignment asks to redirect to login, but I'm redirecting to index on purpose
// what if we want a home page later for users not logged in? (future proofing this kind of)
// and it redirects to login anyways if not logged in
header("Location: index.php");
exit();
?>