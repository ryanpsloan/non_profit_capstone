<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
include("../event.php");


try {
	$mysqli = MysqliConfiguration::getMysqli();
	if(($mysqli = MysqliConfiguration::getMysqli()) === false) {
		throw (new mysqli_sql_exception("Server connection failed, please try again later."));
	}

	if(@isset($_POST['eventTitle']) === null || @isset($_POST['eventDate']) === null || @isset($_POST['eventLocation']) === null) {
		echo "<p>Form variables incomplete or missing. Please refill form</p>";
	}

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	$newEvent = new Event(null, $_POST["eventTitle"], $_POST["eventDate"], $_POST["eventLocation"]);
	var_dump($mysqli);
	$newEvent->insert($mysqli);

	$joinUserEvent = new UserEvent($_SESSION["profileId"],$event->eventId, 1, 1, 1);
	$joinUserEvent->insert($mysqli);

	echo "<p class=\"alert alert-success\" role=\"alert\">Event posted!</p>";
} catch (Exception $exception){
	echo "We have encountered an error." . " " . $exception->getMessage();
}