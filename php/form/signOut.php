<?php
/**
 * SignOut form processor
 * User: Martin
 */

session_start();

foreach($_SESSION as $key => $value) {
	unset($_SESSION[$key]);
}

session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="refresh" content="0; url=../../index.php">
</head>
</html>

