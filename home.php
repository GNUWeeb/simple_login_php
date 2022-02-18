<?php
// SPDX-License-Identifier: GPL-2.0-only
session_start();

if (!isset($_SESSION["user"])) {
	header("Location: login.php");
	exit(0);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome <?= htmlspecialchars("{$_SESSION["user"]["first_name"]} {$_SESSION["user"]["last_name"]}"); ?></title>
</head>
<body>
	<center>
		<a href="logout.php"><button>Logout</button></a>
		<h1>Welcome <?= htmlspecialchars("{$_SESSION["user"]["first_name"]} {$_SESSION["user"]["last_name"]}"); ?></h1>
	</center>
</body>
</html>
