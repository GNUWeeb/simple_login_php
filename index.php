<?php
// SPDX-License-Identifier: GPL-2.0-only
session_start();

if (!isset($_SESSION["user"])) {
	require __DIR__."/login.php";
	exit;
}
