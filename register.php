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

    if (!isset($username) || strlen($username) === 0 || username_exists($pdo, $username)) {
        $errors['user'] = true;
    }

    if (!isset($name) || strlen($name) === 0) {
        $errors['name'] = true;
    }

    if (!isset($email) || strlen($email) === 0) {
        $errors['email'] = true;
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

    if ($password !== $_POST['VerifyPassword']) {
        $errors['VerifyPassword'] = true;
    }


    if (count($errors) === 0) {
        $query = "INSERT INTO `library_users` (`id`, `username`, `password`, `name`, `email`) VALUES (NULL, ?, ?, ?, ?) ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $name, $email]);
        header("Location: login.php");
        exit();
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
    <title>Bata - Register</title>
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
                    <input type="text" name="username" id="username" placeholder="Username" required value=<?= $username ?> />
                </div>
                <span class="<?= !isset($errors['user']) ? 'hidden' : ""; ?>">*That user already exists</span>
                <div>
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" placeholder="John Doe" required value=<?= $name ?> />
                </div>
                <span class="<?= !isset($errors['name']) ? 'hidden' : ""; ?>">*No name entered</span>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="johndoe@trentu.ca" value=<?= $email ?> />
                </div>
                <span class="<?= !isset($errors['email']) ? 'hidden' : ""; ?>">*Enter an email</span>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" required />
                </div>
                <span class="<?= !isset($errors['password']) ? 'hidden' : ""; ?>"><?= $passwordErr ?></span>
                <div>
                    <label for="VerifyPassword">Verify Password:</label>
                    <input type="password" name="VerifyPassword" id="VerifyPassword" placeholder="Verify Password"
                        required />
                </div>
                <span class="<?= !isset($errors['VerifyPassword']) ? 'hidden' : ""; ?>">*Passwords do no not
                    match</span>
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