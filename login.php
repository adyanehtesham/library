<?php

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$errors = [];

if (isset($_POST['submit'])) {
    include 'includes/library.php';

    $pdo = connectDB();

    $query = "SELECT * from library_users where username=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $result = $stmt->fetch();
    // var_dump($result); //  debugger
    if (!$result) {
        $errors['user'] = true;
    } else {
        if (password_verify($password, $result['password'])) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $result['id'];
            header("Location: index.php");
            exit();
        } else {
            $errors['login'] = true;
        }
    }

}

?>
<!DOCTYPE html>
<html lang="en">

<!-- Setting up the page's metadata -->

<head>
    <?php
    $page_title = "Bata - Login";
    include "includes/metadata.php"
        ?>
</head>

<!-- body consists of header, main body and footer -->

<body>
    <!-- Header consists of website title and logo and a navbar with links to various pages -->
    <?php include "includes/header.php" ?>

    <!-- Main section of the page that has the form for login-->
    <main>
        <section>
            <h2>Login</h2>
            <!-- Image of an avatar reading -->
            <img class="torsoReading" src="./images/icons/TorsoReading.svg" alt="Doodle of a person reading">
            <!-- Login form -->
            <form id="login_form" class="accountForm" name="login_form"
                action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Username" required
                        value="<?= $username; ?>" />
                </div>
                <div>
                    <label for=" password">Password:</label>
                    <input type="password" name="password" id="password" placeholder="Password" required />
                </div>
                <div>
                    <span class="<?= !isset($errors['user']) ? 'hidden' : ""; ?>">*That user doesn't exist</span>
                    <span class="<?= !isset($errors['login']) ? 'hidden' : ""; ?>">*Incorrect login info</span>
                </div>
                <div class="rememberMe">
                    <input type="checkbox" name="remember" id="remember" />
                    <label for="remember">Remember me</label>
                </div>
                <div class="loginButtons">
                    <button class="button" type="submit" name="submit" id="submit">
                        Login
                    </button>
                    <a class="button" href="./register.php">Register</a>
                </div>
                <!-- Link to Forgot password page incase user forgot their password-->
                <div>
                    <a class="forgotPassword" href="./forgot.php">Forgot password?</a>
                </div>
            </form>
        </section>
    </main>
    <!-- Footer has my name and where I got the design inspiration from -->
    <? include "includes/footer.php" ?>

</body>

</html>