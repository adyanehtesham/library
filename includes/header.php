<header>
	<div class="title">
		<img src="./images/icons/SitReadingDoodle.svg" alt="Person Sitting and Reading" height="64" width="64" />
		<h1><a href="./index.php">Bata Online</a></h1>
	</div>
	<!-- Nav that holds the links -->
	<nav>
		<ul>
			<?php
			if (!isset($_SESSION)) {
				session_start();
			}
			$logged_in = false;
			if (isset($_SESSION['username'])) {
				$logged_in = true;
			}
			if ($logged_in) { ?>
				<li class="button">
					<img class="icon" src="./images/icons/home.svg" alt="" /><a href="./index.php">Home</a>
				</li>
				<li class="button">
					<img class="icon" src="./images/icons/search.svg" alt="" /><a href="./search.php">Search</a>
				</li>
				<li class="button">
					<a href="./editaccount.php">Settings</a>
				</li>
				<li class="button">
					<a href="./logout.php">Logout</a>
				</li>
			<?php }

			if (!$logged_in) { ?>

				<li class="button">
					<a href="./login.php">Login</a>
				</li>
				<li class="button">
					<a href="./register.php">Register</a>
				</li>
			<?php } ?>
		</ul>
	</nav>
</header>