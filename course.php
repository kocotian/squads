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
				Welcome to the <b>Nevada Squads</b>, a privacy oriented e-learning platform
			</p>
			<center>You are logged in as <b><?= $userdata['username'] ?></b>. <a href="logout.php">Logout</a></center>
			<br />
			<?php
				$course = db::query("SELECT * FROM courses WHERE id=:id", [':id' => $_GET['id']])[0];
			?>
			<h2 class="t_green"><?= $course['title'] ?></h2>
			<p>
				<?= $course['description'] ?><br />
				Created by <b><?= userAccount::idToUsername($course['creatorId']) ?></b> in <?= $course['creationDate'] ?>
			</p>
			<h3 class="t_blue">Course files:</h3>
			<h3 class="t_blue">Course tests:</h3>
			<?php
				$tests = db::query("SELECT * FROM tests WHERE courseId=:courseId", [':courseId' => $course['id']]);
				if (!count($tests))
					echo "Course doesn't have any tests yet";
				else
				foreach ($tests as $test) {
					echo '<a class="clearlink" href="test.php?id=' . $test['id'] . '"><div class="card">
						<b class="larger">' . $test['title'] . '</b><br />
						' . $test['description'] . '<br />
						Created by <b>' . userAccount::idToUsername($test['creatorId']) . '</b>
					</div></a>';
				}
			?>

			<?= $copyrightchunk ?>
		</div>
	</html>
</html>
