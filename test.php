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
				$test = db::query("SELECT * FROM tests WHERE id=:id", [':id' => $_GET['id']])[0];
				$testQuestions = db::query("SELECT * FROM testQuestions WHERE testId=:testId", [':testId' => $test['id']]);
			?>
			<h2 class="t_green"><?= $test['title'] ?></h2>
			<p>
				<?= $test['description'] ?><br />
				Created by <b><?= userAccount::idToUsername($test['creatorId']) ?></b> in <?= $test['creationDate'] ?>
			</p>
			<?php
				if ($test['creatorId'] == $userId) {
					$testSentQuestions = db::query("SELECT * FROM testSentQuestions WHERE testId=:testId", [':testId' => $test['id']]);
					echo '<p>
						<h3 class="t_cyan">Completed by:</h3>';
					foreach ($testSentQuestions as $testSentQuestion) {
					echo '<span class="tag"><b>' . userAccount::idToUsername($testSentQuestion['userId']) . '</b> (' . $testSentQuestion['correctAnswers'] . ' / ' . count($testQuestions) . ')</span>';
					}
					echo '</p><br />';
				}
			?>
			<form action="testSender.php?id=<?= $test['id'] ?>" method="POST">
				<?php
					if (!count($testQuestions))
						echo "Test doesn't have any questions yet";
					else {
						$index = 0;
						foreach ($testQuestions as $testQuestion) {
							echo '<div class="card">';
							if (strlen($testQuestion['answers'])) {
								/* closed question */
								$answers = explode('|', $testQuestion['answers']);
								$answerindex = 0;
								echo '<span class="larger">Question ' . ($index + 1) . ': <b>' . $testQuestion['question'] . '</b></span><br /><br />';
								foreach ($answers as $answer) {
									echo '<input type="radio" name="question_' . $index . '" id="answer_' . $answerindex . '" value="' . $answerindex . '" />
									<label for="answer_' . $answerindex . '">' . $answer . '</label><br />';
									++$answerindex;
								}
							} else {
								/* open question */
								echo '<span class="larger">Question ' . ($index + 1) . ': <b>' . $testQuestion['question'] . '</b></span><br /><br />
								<input type="text" placeholder="answer" name="question_' . $index . '" />';
							}
							++$index;
							echo '</div><br />';
						}
					}
				?>
				<input type="submit" name="sender" value="Send" />
			</form>

			<?= $copyrightchunk ?>
		</div>
	</html>
</html>
