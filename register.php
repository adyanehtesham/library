<?php

$errors = array(); //declare empty array to add errors too

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;

// function to check if usename exists
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

if (isset($_POST['submit'])) {
    include 'includes/library.php';

    $pdo = connectDB();
    $query = "INSERT INTO `library_users` (`id`, `username`, `password`, `name`, `email`) VALUES (NULL, ?, ?, ?, ?) ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $name, $email]);
    header("Location: login.php");
    exit();


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
    <title>Bata - Register</title>
    <script defer src="scripts/strength.js"></script>
    <script defer src="scripts/registervalidation.js"></script>
</head>

<!-- body consists of header, main body and footer -->

<body>
    <!-- Header consists of website title and logo and a navbar with links to various pages -->
    <?php include "includes/header.php" ?>

    <!-- Main section of the page that has the form for user to sign up-->
    <main>
        <section>
            <h2>Register</h2>
            <!-- Register Form -->
            <form id="register_form" class="accountForm" name="register_form"
                action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Username" value="<?= $username ?>" />
                </div>
                <span class="hidden">*No username entered</span>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" placeholder="John Doe" value="<?= $name ?>" />
                </div>
                <span class="hidden">*No name entered</span>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="johndoe@trentu.ca" value="<?= $email ?>" />
                </div>
                <span class="hidden">*Enter an email</span>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" value="" />
                </div>
                <figure class="hidden">
                    <figcaption>Password must have</figcaption>
                    <ul>
                        <li>At least 8 characters</li>
                        <li>1 Uppercase Character</li>
                        <li>1 Lowercase Character</li>
                        <li>1 Number</li>
                        <li>1 Special Character</li>
                    </ul>
                </figure>
                <span id="strength-text"></span>
                <div>
                    <label for="VerifyPassword">Verify Password:</label>
                    <input type="password" name="VerifyPassword" id="VerifyPassword" placeholder="Verify Password" />
                </div>
                <span class="hidden">*Passwords do no not match</span>
                <div>
                    <span id="error" class="error"></span>
                </div>
                <div>
                    <button class="button" type="submit" name="submit" id="submit">
                        Register
                    </button>
                </div>
            </form>
            <!-- Link to login page, if user is already a user -->
            <div>Already a user? <a href="./login.php">Log In</a></div>
        </section>
    </main>
    <!-- Footer has my name and where I got the design inspiration from -->
    <?php include "includes/footer.php" ?>

</body>

</html>