<?php
	session_start();
	require_once("../php/form/csrf.php");
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
		<body>
			<form id="eventCreation" action="../php/form/eventcreationprocessor.php" method="post">
				<?php echo generateInputTags()?>
				<label for="eventTitle">What is the name of your Event?</label>
				<br/>
				<input type="text" name="eventTitle">
				<br/>
				<br/>
				<label for="eventDate">When is your event taking place?</label>
				<br/>
				<p>Please enter it in YYYY-MM-DD HH:ii:ss format.</p>
				<input type="text" name="eventDate">
				<br/>
				<br/>
				<label for="eventLocation">Where will your event take place?</label>
				<br/>
				<input type="text" name="eventLocation">
				<br/>
				<input type="submit" value="Create event!">
			</form>
		</body>
	</html>