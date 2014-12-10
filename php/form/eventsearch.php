<?php
	session_start();
	require_once("/etc/apache2/capstone-mysql/helpabq.php");
	require_once("../php/form/csrf.php");
	require_once("../event.php");
	$mysqli = $mysqli = MysqliConfiguration::getMysqli();
	$event = Event::getEventByEventId($mysqli, $_POST("eventId"));

	function editCheck(){

	}

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
	<title><?php $event->eventTitle?></title>
</head>
<body>
	<?php /*navBar()*/?>
	<aside>
		<?php
			echo "<h3>$event->eventTitle</h3>";
			echo "<h5>$event->eventDate</h5>";
			echo "<h5>$event->eventLocation</h5>";
		?>
	</aside>


</body>