<?php
	# vim:ft=html
	require_once "require/db.php";
	require_once "require/user.php";
	session_start();

	if (!(isset($_SESSION['acrAuthToken']))) {
	    $cstrong = true;
	    $_SESSION['acrAuthToken'] = bin2hex(openssl_random_pseudo_bytes(24, $cstrong));
	}

	$userId = userAccount::isUserLoggedIn();
	if ($userId) {
	    header('Location: /');
	    exit(0);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="styles/styles.css" />
		<link rel="stylesheet" href="styles/forms.css" />
		<title>Nevada Squads</title>
	</head>
	<html>
		<div class="container">
			<h1 class="t_red">Nevada Squads</h1>
			<p class="t_center">
				Registration.
			</p>
			<center>
				<?= isset($_GET['err']) ? '<center class="t_red">' . urldecode($_GET['err']) . '</center><br />' : "" ?>
				<form action="reg.php" method="POST">
					<input type="text" placeholder="username" name="username" /><br />
					<input type="password" placeholder="password" name="password" /><br />
					<input type="submit" value="Register" name="submit" />
					<a href="login.php"><input class="red" type="button" value="Log in" name="login" /></a>
				</form>
			</center>
			<?= $copyrightchunk ?>
		</div>
	</html>
</html>
