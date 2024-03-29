<?php
	/**
	 * Form to create events and add them to the data base.
	 *
	 * @author Dameon Smith <dameonsmith76@gmail.com>
	 */
	session_start();
	require_once("../php/form/csrf.php");
	require_once("navbar.php");
	require_once("../php/profile.php");
	require_once("../php/user.php");
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
			<link type="text/css" href="sign.css" rel="stylesheet">
			<link type="text/css" href="../index1.css" rel="stylesheet">
		</head>
		<body>
			<?php navBarForm() ?>
			<div class="container">
				<div id="outputArea"></div>
				<form id="eventCreation" class="form-signin" action="../php/form/eventcreationprocessor.php" method="post">
					<?php echo generateInputTags()?>
					<label for="eventTitle">What is the name of your Event?</label>
					<br/>
					<input type="text" class="form-signin form-control" name="eventTitle">
					<br/>
					<br/>
					<label for="eventDate">When is your event taking place?</label>
					<br/>
					<p>Please enter it in YYYY-MM-DD HH:ii:ss format.</p>
					<input type="text" class="form-signin form-control " name="eventDate">
					<br/>
					<br/>
					<label for="eventLocation">Where will your event take place?</label>
					<br/>
					<input type="text" class="form-signin form-control" name="eventLocation">
					<br/>
					<input type="submit" class="btn btn-primary" value="Create event!">
				</form>
			</div>
		</body>
	</html>