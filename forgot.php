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
            <form class="accountForm" id="forgot_form" name="forgot_form" action="forgotForm.php" method="post">
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