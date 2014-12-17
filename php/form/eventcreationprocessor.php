<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../../form_front_facing/navbar.php");
include("../event.php");
include("../userevent.php");
?>


	<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="refresh" content="5;../../index.php" />
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/j	avascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../../form_front_facing/searchEvent.js"></script>
		<link type="text/css" rel="stylesheet" href="../../form_front_facing/sign.css"/>
		<link type="text/css" rel="stylesheet" href="../../index1.css"/>

		<title>Join Event Form</title>
	</head>
<body>
<?php navBarProcessor() ?>
	<div class="container">

<?php


try {

	if(($mysqli = MysqliConfiguration::getMysqli()) === false) {
		throw (new mysqli_sql_exception("Server connection failed, please try again later."));
	}

	if(@isset($_POST['eventTitle']) === null || @isset($_POST['eventDate']) === null || @isset($_POST['eventLocation']) === null) {
		echo "<p>Form variables incomplete or missing. Please refill form</p>";
	}

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	$mysqli = MysqliConfiguration::getMysqli();
	$newEvent = new Event(null, $_POST["eventTitle"], $_POST["eventDate"], $_POST["eventLocation"]);
	$newEvent->insert($mysqli);

	$joinUserEvent = new UserEvent($_SESSION["profileId"],$newEvent->eventId, 1, 1, 1);
	$joinUserEvent->insert($mysqli);


	echo"<div class='alert alert-info' role='alert'>Thank you for creating an event:" . $newEvent->eventTitle." </div>";

} catch (Exception $exception){
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to create a new event: " . $exception->getMessage() . "</div>";

}
?>

	</div>
</body>
</html>