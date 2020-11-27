<?php
    require_once('require/db.php');
    require_once('require/user.php');

    if (userAccount::isUserLoggedIn()) {
        if (isset($_COOKIE['squadsLoginToken']))
            db::query("DELETE FROM authTokens WHERE token=:token", [':token' => sha1($_COOKIE['squadsLoginToken'])]);

        setcookie('squadsLoginToken', '0', time() - 3600);
        setcookie('squadsIsTokenUnexpired', '0', time() - 3600);
    }
	header('Location: login.php'); exit(0);
