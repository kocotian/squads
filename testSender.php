<?php

	require_once "require/db.php";
	require_once "require/user.php";
	session_start();

	$userId = userAccount::isUserLoggedIn();
	if (!$userId) {
	    header('Location: login.php');
	    exit(0);
	}

	$test = db::query("SELECT * FROM tests WHERE id=:id", [':id' => $_GET['id']])[0];
	$testQuestions = db::query("SELECT * FROM testQuestions WHERE testId=:testId", [':testId' => $test['id']]);

	$correct = 0;
	$PSV = [];
	$index = -1;
	foreach ($testQuestions as $testQuestion) {
		array_push($PSV, $_POST['question_' . ++$index]);
		if (!strcmp($testQuestion['correctAnswer'], $_POST['question_' . $index]))
			++$correct;
	}

	db::query("INSERT INTO testSentQuestions (userId, testId, questionsPSV, correctAnswers) VALUES (:userId, :testId, :questionsPSV, :correctAnswers)", [':userId' => $userId, ':testId' => $test['id'], ':questionsPSV' => implode('|', $PSV), ':correctAnswers' => $correct]);
	header('Location: test.php?id=' . $test['id']);
