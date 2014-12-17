<!--* join event form processor-->
<!--* User: Martin-->


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
	<script type="text/j	avascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../../form_front_facing/searchEvent.js"></script>
	<link type="text/css" rel="stylesheet" href="../../form_front_facing/sign.css"/>

	<title>Join Event Form</title>
</head>
<body>
<div class="container">

<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../userevent.php");
require_once("../event.php");



// verify CSRF
try {
	if(@isset($_POST["eventId"]) === false) {
		throw(new RuntimeException("Please enter eventName"));
	}

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}
// create a UserEvent object with the profileId and eventId
	$mysqli    = MysqliConfiguration::getMysqli();
	$event = Event::getEventByEventId($mysqli, $_POST["eventId"]);
				if ($event === null) {
					throw (new UnexpectedValueException ("No Event found"));
	}

	$joinUserEvent = new UserEvent($_SESSION["profileId"],$event->eventId, 1, 1, 1);

// insert into mySQL
	$joinUserEvent->insert($mysqli);

	echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Thank you for supporting event: " . $event->eventTitle . "</strong>  </div>";


} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to join event: " . $exception->getMessage() . "</div>";

}

?>


</div>
</body>
</html>