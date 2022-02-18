<?php 
// SPDX-License-Identifier: GPL-2.0-only
session_start();
session_destroy();
header("Location: index.php");
