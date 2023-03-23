<?php
# forgot password page
include 'includes/library.php';

// define constants
define('FROM_EMAIL', '<noreply@loki.trentu.ca>');
define('RESET_SUBJECT', 'Main Page - Password Reset');

// Check if form was submitted
if (isset($_POST['submit'])) {

    // setting variables for username, email
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    $errors = [];

    // Validate input
    if (empty($username) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid input';
    }

    // Check if user exists
    $pdo = connectDB();
    $stmt = $pdo->prepare('SELECT * FROM `library_users` WHERE username = ? AND email = ?');
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errors[] = 'User not found';
    } else {
        // Generate a unique token and update the database
        $token = uniqid(); // newpassword
        $stmt = $pdo->prepare('UPDATE `library_users` SET password = ? WHERE username = ?');
        $hash = password_hash($token, PASSWORD_DEFAULT);
        $stmt->execute([$hash, $username]);

        require_once 'Mail.php';
        // Send password reset email
        $to = $user['email'];
        $body = "new password regenerated {$token}";
        $host = "smtp.trentu.ca";
        $headers = ['From' => FROM_EMAIL, 'To' => $to, 'Subject' => RESET_SUBJECT];

        // Send email
        $smtp = Mail::factory('smtp', ['host' => "smtp.trentu.ca"]);
        $mail = $smtp->send($to, $headers, $body);

        // Check if email was sent
        if (PEAR::isError($mail)) {
            $errors[] = 'Email not sent';
        } else {
            header('Location:login.php');
            exit();
        }
    }

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>Error: $error</p>";
        }
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
    <title>Bata - Forgot Password</title>
</head>

<!-- body consists of header, main body and footer -->

<body>
    <!-- Header consists of website title and logo and a navbar with links to various pages -->
    <?php include "includes/header.php" ?>

    <!-- Main section of the page that has the form to enter details for a password reset -->
    <main>
        <section>
            <h2>Forgot Password</h2>
            <form class="accountForm" id="forgot_form" name="forgot_form"
                action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" placeholder="Username" required />
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="johndoe@trentu.ca" required />
                </div>
                <div>
                    <button class="button" type="submit" name="submit" id="submit">
                        Reset Password
                    </button>
                </div>
            </form>
        </section>
    </main>
    <!-- Footer has my name and where I got the design inspiration from -->
    <? include "includes/footer.php" ?>

</body>

</html>