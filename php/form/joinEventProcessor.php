<?php
/**
 * join event form processor
 * User: Martin
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../userEvent.php");
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

	$joinUserEvent = new UserEvent($_SESSION["profileId"],$event->eventId, 1, 1, 1);

// insert into mySQL
	$joinUserEvent->insert($mysqli);
} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to join event: " . $exception->getMessage() . "</div>";

}

?>