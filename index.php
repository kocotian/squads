<?php
	# vim:ft=html
	require_once "require/chunks.php";
	require_once "require/db.php";
	require_once "require/user.php";
	session_start();

	if (!(isset($_SESSION['acrAuthToken']))) {
	    $cstrong = true;
	    $_SESSION['acrAuthToken'] = bin2hex(openssl_random_pseudo_bytes(24, $cstrong));
	}

	$userId = userAccount::isUserLoggedIn();
	if ($userId)
	    $userdata = db::query("SELECT * FROM users WHERE id=:userId", [':userId' => $userId])[0];
	else {
	    header('Location: login.php');
	    exit(0);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="styles/styles.css" />
		<title>Nevada Squads</title>
	</head>
	<html>
		<div class="container">
			<h1 class="t_red">Nevada Squads</h1>
			<p class="t_center">
				Welcome in <b>Nevada Squads</b>, a privacy oriented e-learning platform
			</p>
			<center>You are logged in as <b><?= $userdata['username'] ?></b>. <a href="logout.php">Logout</a></center>
			<?= $copyrightchunk ?>
		</div>
	</html>
</html>
