<?php
# vim:ft=html
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
				You need to <b>log in</b>.
			</p>
			<center>
				<form action="auth.php" method="POST">
					<input type="text" placeholder="username" name="username" /><br />
					<input type="password" placeholder="password" name="password" /><br />
					<input type="submit" value="Log in" name="submit" />
					<input class="red" type="button" value="Register" name="register" />
				</form>
			</center>
		</div>
	</html>
</html>
