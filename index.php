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
				Welcome to the <b>Nevada Squads</b>, a privacy oriented e-learning platform
			</p>
			<center>You are logged in as <b><?= $userdata['username'] ?></b>. <a href="logout.php">Logout</a></center>
			<?= isset($_GET['err'])  ? '<br /><br /><center class="t_red t_large">'   . urldecode($_GET['err'])  . '</center><br />' : "" ?>
			<br />
			<h1>Courses</h1>
			<p>
				<h2 class="t_green">Created courses</h2>
				<?php
					$createdcs = db::query("SELECT * FROM courses WHERE creatorId=:creatorId", [':creatorId' => $userId]);
					if (!count($createdcs))
						echo "You don't have any courses yet.";
					else
					foreach ($createdcs as $created) {
						echo '<a class="clearlink" href="course.php?id=' . $created['id'] . '"><div class="card">
							<b class="larger">' . $created['title'] . '</b><br />
							' . $created['description'] . '
						</div></a>';
					}
				?>
			</p>
			<p>
				<h2 class="t_magenta">Joined courses</h2>
				<?php
					$joinedcs = db::query(
						"SELECT courses.*, courseMemberships.id AS membershipId
						FROM courses, courseMemberships
						WHERE
							courses.id=courseMemberships.courseId
							AND courseMemberships.userId=:userId
							AND courseMemberships.adminLevel=0",
					[':userId' => $userId]);
					if (!count($joinedcs))
						echo "You don't have any courses yet.";
					else
					foreach ($joinedcs as $joined) {
						echo '<a class="clearlink" href="course.php?id=' . $joined['id'] . '"><div class="card">
							<b class="larger">' . $joined['title'] . '</b><span class="larger">, created by <b>' . userAccount::idToUsername($joined['creatorId']) . '</b></span><br />
							' . $joined['description'] . '
						</div></a>';
					}
				?>
			</p>
			<p>
				<h2 class="t_blue">Course invites</h2>
				You don't have any course invites yet.
			</p>
			<p>
				<h2 class="t_yellow">Join course</h2>
				<form action="joinCourse.php" method="GET">
					<input type="text" name="id" placeholder="Course ID" />
					<input class="t_black yellow" type="submit" name="submit" />
				</form>
			</p>
			<?= $copyrightchunk ?>
		</div>
	</html>
</html>
