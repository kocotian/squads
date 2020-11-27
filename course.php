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
				Welcome to the <b>Nevada Squads</b>, a less bloated, privacy oriented and open source e-learning platform
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
				<br /><br />
				CID: <b><?= substr(sha1($course['id']), 0, 4) . $course['id'] ?></b>
			</p>
			<h3 class="t_blue">Course content:</h3>
			<?php
				$contents = db::query("SELECT * FROM contents WHERE courseId=:courseId", [':courseId' => $course['id']]);
				if (!count($contents))
					echo "Course doesn't have any content yet";
				else
				foreach ($contents as $content) {
					echo '<a class="clearlink" href="content.php?id=' . $content['id'] . '"><div class="card">
						<b class="larger">' . $content['title'] . '</b><br />
						' . substr(strip_tags($content['content']), 0, 100) . (strlen(strip_tags($content['content'])) > 100 ? '...' : '') . '<br />
						Created by <b>' . userAccount::idToUsername($content['creatorId']) . '</b>
					</div></a>';
				}
			?>
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
			<h3 class="t_blue">Course members:</h3>
			<?php
				$members = db::query("SELECT * FROM courseMemberships WHERE courseId=:courseId", [':courseId' => $course['id']]);
				if (!count($members))
					echo "Course doesn't have any members yet";
				else
				foreach ($members as $member) {
					echo '<span class="tag">' . userAccount::idToUsername($member['userId']) . '</span>';
				}
			?>

			<?= $copyrightchunk ?>
		</div>
	</html>
</html>
