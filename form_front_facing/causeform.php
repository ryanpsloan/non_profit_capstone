<?php
/**
 *
 * User: Cass
 * Form to enter a cause
 */
require_once("../php/form/csrf.php");
session_start();
?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</head>

<title>Cause Form</title>

<body>
	<form id="cause" action="../php/form/causeprocessor.php" method="POST">
		<?php echo generateInputTags();?>
		<label for="causeName">Enter Cause you want to support:</label><br>
		<input type="causeName" id="causeName" name="causeName" autocomplete="off"><br>
		<label for="cause">Enter Cause Description:</label><br>
		<input type="causeDescription" id="causeDescription" name="causeDescription" autocomplete="off"><br>
		<input type="submit" value="Submit">
	</form>
</body>
</html>
