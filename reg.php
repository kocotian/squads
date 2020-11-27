<?php

	require_once "require/db.php";
	session_start();

	$user = db::query("SELECT * FROM users WHERE username=:username", [':username' => $_POST['username']])[0];
	if (!$user)
		if (strlen($_POST['password']) > 3 && strlen($_POST['password']) < 33) {
			db::query("INSERT INTO users (username, password) VALUES (:username, :password)", [':username' => $_POST['username'], ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT)]);
			setcookie("squadsLoginToken", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);
			setCookie("squadsIsTokenUnexpired", "1", time() + 60 * 60 * 24 * 3, '/', NULL, NULL, true);
			header("Location: login.php?succ=Now%20you%20can%20log%20in");
		} else
			header("Location: register.php?err=Wrong%20password");
	else
		header("Location: register.php?err=Account%20already%20exists");
