<?php

	require_once "require/db.php";
	require_once "require/user.php";
	session_start();

	$userId = userAccount::isUserLoggedIn();
	if (!$userId) {
	    header('Location: login.php');
	    exit(0);
	}

	$cid = substr($_GET['id'], 4);
	if (strcmp(substr(sha1($cid), 0, 4), substr($_GET['id'], 0, 4)))
		exit(header('Location: /?err=Invalid%20cid'));

	$course = db::query("SELECT * FROM courses WHERE id=:id", [':id' => $cid])[0];
	if ($course['privacy'] == 2)
		exit(header('Location: /?err=Course%20is%20private.'));

	$courseMembership = db::query("SELECT * FROM courseMemberships WHERE userId=:userId AND courseId=:courseId", [':userId' => $userId, ':courseId' => $course['id']]);
	if (count($courseMembership))
		exit(header('Location: /?err=You%20are%20already%20in%20this%20course.'));

	db::query("INSERT INTO courseMemberships (userId, courseId, isConfirmed) VALUES (:userId, :courseId, :isConfirmed)", [':userId' => $userId, ':courseId' => $course['id'], ':isConfirmed' => ($course['private'] ? 0 : 1)]);
	header('Location: course.php?id=' . $course['id']);
