<?php

	require_once "require/db.php";
	session_start();

	$user = db::query("SELECT * FROM users WHERE username=:username", [':username' => $_POST['username']])[0];
	if ($user)
		if (password_verify($_POST['password'], $user['password'])) {
			$cstrong = true;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
			db::query("INSERT INTO authTokens (userId, token) VALUES (:userId, :token)", [':userId' => $user['id'], ':token' => sha1($token)]);
			setcookie("squadsLoginToken", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);
			setCookie("squadsIsTokenUnexpired", "1", time() + 60 * 60 * 24 * 3, '/', NULL, NULL, true);
			header("Location: index.php?login=success");
		} else
			header("Location: login.php?err=Wrong%20password");
	else
		header("Location: login.php?err=Unexisting%20account");
