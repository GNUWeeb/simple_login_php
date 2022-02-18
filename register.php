<?php
// SPDX-License-Identifier: GPL-2.0-only
if (isset($_POST["username"], $_POST["first_name"], $_POST["last_name"], $_POST["password"], $_POST["cpassword"])) {

	$code = 400;
	$redirect = NULL;

	if (!is_string($_POST["username"])) {
		$msg = "username must be a string";
		goto out;
	}

	if (!is_string($_POST["first_name"])) {
		$msg = "first_name must be a string";
		goto out;
	}

	if (!is_string($_POST["last_name"])) {
		$msg = "last_name must be a string";
		goto out;
	}

	if (!is_string($_POST["password"])) {
		$msg = "password must be a string";
		goto out;
	}

	if (!is_string($_POST["cpassword"])) {
		$msg = "cpassword must be a string";
		goto out;
	}

	$_POST["first_name"] = trim($_POST["first_name"]);
	$_POST["last_name"] = trim($_POST["last_name"]);
	$_POST["username"] = trim(strtolower($_POST["username"]));
	$db = json_decode(file_get_contents(__DIR__."/accounts.json"), true);
	$ref = &$db[$_POST["username"]];
	if (isset($ref)) {
		$msg = "Username {$_POST["username"]} has already been registered, please use another username!";
		goto out;
	}

	$pat = '/^[a-zA-Z0-9\.\_]{6,}+$/';
	if (!preg_match($pat, $_POST["username"])) {
		$msg = "Username must match the regex pattern {$pat}";
		goto out;
	}

	$c = strlen($_POST["password"]);
	if ($c < 6) {
		$msg = "Password is too short, please provide a password at least 6 bytes";
		goto out;
	}

	if ($_POST["password"] !== $_POST["cpassword"]) {
		$msg = "Confirm password must be the same with password";
		goto out;
	}

	if (empty($_POST["first_name"])) {
		$msg = "First name cannot be empty!";
		goto out;
	}

	$db[$_POST["username"]] = [
		"first_name" => $_POST["first_name"],
		"last_name" => $_POST["last_name"],
		"password" => password_hash($_POST["password"], PASSWORD_BCRYPT)
	];

	file_put_contents(__DIR__."/accounts.json", json_encode($db, JSON_PRETTY_PRINT), LOCK_EX);

	$code = 200;
	$redirect = "index.php";
out:
	http_response_code($code);
	header("Content-Type: application/json");
	echo json_encode([
		"code" => $code,
		"msg" => $msg,
		"redirect" => $redirect
	]);
	exit(0);
}

?><!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<style type="text/css">
		body {
			display: flex;
			min-height: 100vh;
			align-items: center;
			justify-content: center;
		}
		.form-cage {
			width: 400px;
			height: 500px;
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
	</style>
</head>
<body>
	<div class="form-cage">
		<form id="form" method="POST" action="javascript:void(0);">
			<?php if (isset($alert)): ?>
			<?php echo htmlspecialchars($alert); ?>
			<?php endif ?>
			<h1>Register</h1>
			<div class="label">Username</div>
			<div class="input"><input type="text" name="username" required/></div>
			<div class="label">First Name</div>
			<div class="input"><input type="text" name="first_name" required/></div>
			<div class="label">Last Name</div>
			<div class="input"><input type="text" name="last_name"/></div>
			<div class="label">Password</div>
			<div class="input"><input type="password" name="password" required/></div>
			<div class="label">Confirm Password</div>
			<div class="input"><input type="password" name="cpassword" required/></div>
			<div class="button"><button type="submit">Register</button></div>
		</form>
	</div>
	<script type="text/javascript">
		let form = document.getElementById("form");
		form.addEventListener("submit", function () {
			let ch = new XMLHttpRequest;
			ch.onreadystatechange = function () {
				if (this.readyState !== 4)
					return;

				let json = JSON.parse(this.responseText);
				if (json.msg)
					alert(json.msg);
				if (json.redirect)
					window.location = json.redirect;
			};
			ch.open("POST", "");
			ch.send(new FormData(form));
		});
		
	</script>
</body>
</html>
