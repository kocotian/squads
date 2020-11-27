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
		<link rel="stylesheet" href="styles/forms.css" />
		<title>Nevada Squads</title>
	</head>
	<html>
		<div class="container">
			<h1 class="t_red">Nevada Squads</h1>
			<p class="t_center">
				Welcome to the <b>Nevada Squads</b>, a less bloated, privacy oriented and open source e-learning platform
			</p>
			<center>You are logged in as <b><?= $userdata['username'] ?></b>. <a href="logout.php">Logout</a></center>
			<br />
			<?php
				$content = db::query("SELECT * FROM contents WHERE id=:id", [':id' => $_GET['id']])[0];
			?>
			<h2 class="t_green"><?= $content['title'] ?></h2>
			<p>
				<?= $content['content'] ?>

				<br /><br /><br />
				-----<br /><br />
				Created by <b><?= userAccount::idToUsername($content['creatorId']) ?></b> in <?= $content['creationDate'] ?>
			</p>

			<?= $copyrightchunk ?>
		</div>
	</html>
</html>
