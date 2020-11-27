<?php

    class userAccount
    {
        public static function isUserLoggedIn()
        {
            if (isset($_COOKIE['squadsLoginToken'])) {
                $authTokens = db::query("SELECT * FROM authTokens WHERE token=:token", [':token' => sha1($_COOKIE['squadsLoginToken'])]);
                if ($authTokens) {
                    if (isset($_COOKIE['squadsIsTokenUnexpired']))
                        return $authTokens[0]['userId'];
                    else {
                        $userId = $authTokens[0]['userId'];
                        $cstrong = true;
                        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));

                        db::query("INSERT INTO authTokens(token, userId) VALUES (:token, :userId)", [':token' => sha1($token), ':userId' => $authTokens[0]['userId']]);
                        db::query("DELETE FROM authTokens WHERE token=:token", [':token' => sha1($_COOKIE['squadsLoginToken'])]);

                        setcookie("squadsLoginToken", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, true);
                        setCookie("squadsIsTokenUnexpired", "1", time() + 60 * 60 * 24 * 3, '/', NULL, NULL, true);

                        return $authTokens[0] -> userId;
                    }
                } else return false;
            } else return false;
        }

        public static function usernameToId($username)
        {
            $id = db::query("SELECT id FROM users WHERE username=:username", [':username' => $username]);
            if ($id) return $id[0]['id'];
            else return false;
        }

        public static function idToUsername($id)
        {
            $username = db::query("SELECT username FROM users WHERE id=:id", [':id' => $id]);
            if ($username) return $username[0]['username'];
            else return false;
        }
    }

?>
