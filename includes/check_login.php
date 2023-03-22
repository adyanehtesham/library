<?php
/****************************************
// ENSURES THE USER HAS ACTUALLY LOGGED IN
// IF NOT REDIRECT TO THE LOGIN PAGE HERE
******************************************/
session_start();
if (!(isset($_SESSION['username']))) {
	header("Location: ../assn3/login.php");
	exit();
}

require "includes/library.php";

// CONNECT TO DATABASE
$pdo = connectDB();

// get the contents of naughty/nice list, behavior first so we can use PDO Fetch_group to get two different arrays
$query = "SELECT behavior, name, item FROM cois3420_naughtynice_options,cois3420_naughtynice_alldata where cois3420_naughtynice_alldata.primary_choice = cois3420_naughtynice_options.ID  ORDER BY name ASC";
$stmt = $pdo->query($query);
if (!$stmt) {
	die("Something went horribly wrong");
}
//fetch_groups returns a multidimensional array based on first column of query (all G  and then all B)
//this makes it easy to output as two lits.
$lists = $stmt->fetchAll(PDO::FETCH_GROUP);

?>