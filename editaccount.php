<?php


session_start();
$username = $_SESSION['username'];
include 'includes/library.php';
$pdo = connectDB();

// fetch account details
$query = "SELECT * from library_users where username=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username]);
$result = $stmt->fetch();

// set variables
$username = $_POST['username'] ?? $result['username'];
$password = $_POST['password'] ?? null;
$name = $_POST['name'] ?? $result['name'];
$email = $_POST['email'] ?? $result['email'];

// set errors
$errors = [];

// function to check if username exists
function username_exists($pdo, $username)
{
    $query = "SELECT * from library_users where username=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $result = $stmt->fetch();
    if ($result) {
        return true;
    } else {
        return false;
    }
}

// function to update account details
function update_account($pdo, $field, $value)
{
    $query = "UPDATE `library_users` SET `$field` = ? WHERE `library_users`.`id` = ? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$value, $_SESSION['id']]);
}

if (isset($_POST['submit'])) {

    if ($username != $_SESSION['username'] && username_exists($pdo, $username)) {
        $errors['user'] = true;
    }

    $passwordErr = "Your Password Must Contain At Least 8 Characters, 1 Number, 1 Capital Letter, 1 Lowercase Letter!";

    if (!empty($_POST["password"]) && ($_POST["password"] == $_POST["VerifyPassword"])) {
        $password = $_POST["password"];
        if (strlen($_POST["password"]) <= '8') {
        } elseif (!preg_match("#[0-9]+#", $password)) {
            $errors['password'] = true;
        } elseif (!preg_match("#[A-Z]+#", $password)) {
            $errors['password'] = true;
        } elseif (!preg_match("#[a-z]+#", $password)) {
            $errors['password'] = true;
        }
    } elseif (!empty($_POST["password"])) {
        $VerifyPasswordErr = "Please Check You've Entered Or Confirmed Your Password!";
    } else {
        $passwordErr = "Please enter password   ";
    }

    if ($_POST['password'] != null) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        update_account($pdo, 'password', $password);
    }

    if ($_POST['password'] !== $_POST['VerifyPassword']) {
        $errors['VerifyPassword'] = true;
    }


    if (count($errors) === 0) {
        $query = "UPDATE `library_users` SET `username` = ?, `name` = ?, `email` = ? WHERE `library_users`.`id` = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username, $name, $email, $_SESSION['id']]);
        // header("Location: login.php");
        // exit();
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
    <title>Bata - Account Settings</title>
</head>

<!-- body consists of header, main body and footer -->

<body>
    <!-- Header consists of website title and logo and a navbar with links to various pages -->
    <?php include "includes/header.php" ?>

    <!-- Main section of the page that has the form for user to sign up-->
    <main>
        <section>
            <h2>Account Settings</h2>
            <!-- Register Form -->
            <form id="register_form" class="accountForm" name="register_form"
                action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Username" value="<?= $username ?>" />
                    <span class="<?= !isset($errors['user']) ? 'hidden' : ""; ?>">* That user already exists</span>
                </div>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" placeholder="John Doe" value="<?= $name ?>" />
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="johndoe@trentu.ca" value="<?= $email ?>" />
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" />
                    <span class="<?= !isset($errors['password`']) ? 'hidden' : ""; ?>">* Password is not valid</span>

                </div>
                <div>
                    <label for="VerifyPassword">Verify Password:</label>
                    <input type="password" name="VerifyPassword" id="VerifyPassword" placeholder="Verify Password" />
                    <span class="<?= !isset($errors['VerifyPassword']) ? 'hidden' : ""; ?>">* Passwords don't
                        match</span>
                </div>
                <div>
                    <button class="button" type="submit" name="submit" id="submit">
                        Change
                    </button>
                </div>
            </form>
            <!-- Link to login page, if user is already a user -->
            <div class="danger">Want to delete your account?<a href="./deleteaccount.php">Delete</a></div>
        </section>
    </main>
    <!-- Footer has my name and where I got the design inspiration from -->
    <?php include "includes/footer.php" ?>

</body>

</html>