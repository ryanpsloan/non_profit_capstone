<?php
/**
 *
 * User: Cass
 * Form to enter a Team
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
	<script type="text/javascript" src="team.js"></script>
	<link type="text/css" href="sign.css" rel="stylesheet">
</head>

<title>Team Form</title>

<body>
		<div class="container">
			<div id="outputArea"></div>
			<form id="team" class="form-signin" action="../php/form/teamprocessor.php" method="POST">
				<?php echo generateInputTags();?>
				<label for="teamName">Enter a Team you want to support:</label><br>
					<input type="teamName" class="form-signin form-control" id="teamName" name="teamName" autocomplete="off"><br>
				<label for="team">Enter a Cause for the team:</label><br>
					<input type="teamCause" class="form-signin form-control" id="teamCause" name="teamCause" autocomplete="off"><br>
					<input id = "Submit" class="btn btn-primary" type="submit" value="Submit">
			</form>
		</div>
</body>
</html>
