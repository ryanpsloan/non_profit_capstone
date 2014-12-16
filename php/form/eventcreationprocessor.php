<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
include("../event.php");
include("../userevent.php");


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


	echo"<div class='alert alert-success' role='alert'>Thank you for creating an event:" . $newEvent->eventTitle." </div>";

} catch (Exception $exception){
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to create a new event: " . $exception->getMessage() . "</div>";

}