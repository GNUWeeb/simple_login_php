<?php
// SPDX-License-Identifier: GPL-2.0-only
session_start();

if (isset($_POST["username"], $_POST["password"])) {

	if (!is_string($_POST["username"])) {
		goto html;
	}

	if (!is_string($_POST["password"])) {
		goto html;
	}

	$db = json_decode(file_get_contents(__DIR__."/accounts.json"), true);

	$ref = &$db[$_POST["username"]];
	if (!isset($ref)) {
		$alert = "Wrong username {$_POST["username"]}";
		goto html;
	}

	if (!password_verify($_POST["password"], $ref["password"])) {
		$alert = "Wrong password!";
		goto html;
	}

	echo "Login success!";
	$_SESSION["user"] = $ref;
	$_SESSION["user"]["username"] = $_POST["username"];
	header("Location: home.php");
	exit(0);
}


html:
?><!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<style type="text/css">
		body {
			display: flex;
			min-height: 100vh;
			align-items: center;
			justify-content: center;
		}
		.form-cage {
			width: 400px;
			height: 400px;
			border: 1px solid #000;
			margin: auto;
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
		}
		.label {
			margin-top: 10px;
			margin-bottom: 10px;
		}
		.button {
			margin-top: 20px;
		}
		* {
			font-family: Arial;
		}
		.reg {
			margin-top: 30px;
		}
	</style>
</head>
<body>
	<div class="form-cage">
		<form method="POST" action="">
			<?php if (isset($alert)): ?>
			<?php echo htmlspecialchars($alert); ?>
			<?php endif ?>
			<h1>Login</h1>
			<div class="label">Username</div>
			<div class="input"><input type="text" name="username" required/></div>
			<div class="label">Password</div>
			<div class="input"><input type="password" name="password" required/></div>
			<div class="button"><button type="submit">Login</button></div>
			<div class="reg">Don't have an account? <a href="register.php">Register</a></div>
		</form>
	</div>
</body>
</html>
