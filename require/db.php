<?php

class db
{
	private static function connect()
	{
		try {
			$dbCredentials = require("../squadsdb.php"); /* squadsdb file is in parent of main squads directory 4 security reasons, refer to README */
			return new PDO("mysql:host={$dbCredentials['host']};dbname={$dbCredentials['database']};charset=utf8", $dbCredentials['username'], $dbCredentials['password'], [
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			]);
		} catch(PDOException $error) {
			exit("Database error");
		}
	}

	public static function query($query, $parameters = [])
	{
		$statement = self::connect() -> prepare($query);
		$statement -> execute($parameters);
		if (explode(' ', $query)[0] == 'SELECT')
			return $statement -> fetchAll();
	}
}
