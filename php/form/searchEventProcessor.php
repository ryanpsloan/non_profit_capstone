<?php
/**
 * Create event search processor
 * User: Martin
 */

session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../event.php");
$mysqli    = MysqliConfiguration::getMysqli();

try {
	//verify the form was submitted properly

	if(@isset($_POST["eventTitle"]) === false) {
		throw(new RuntimeException("Please enter an eventTitle to search by."));
	}

	// use filter_input to sanitize event name
	$eventTitle = (filter_input(INPUT_POST, "eventTitle", FILTER_SANITIZE_STRING));

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));

	}
	echo "<div class=\"alert alert-success\" role=\"alert\"><strong><h1>Here are your results</strong></h1>  </div><br/> <br/>";
	// grab mysql data
	$events = Event::getEventByEventTitle($mysqli, $eventTitle);
	/**
	 * return the results to the user
	 */
	$resultCount = count($events);
	for($i = 0; $i < $resultCount; $i++)	{
		$event  = $events[$i];

		echo "<strong>" . $event->eventTitle . "</strong><br/>" .
			$event->eventDate->format("Y-m-d H:i:s") . "<br/>" .
			$event->eventLocation ."<br/>";


		echo "<form id=\"joinEvent\" action=\"joinEventProcessor.php\" method=\"POST\">";
		echo generateInputTags();
		echo "<input type = 'hidden' name = 'eventId' value = \"" . $event->eventId . "\">
		<input id = \"profileSubmit\" type=\"submit\" value=\"Join Event\">
		</form>";

	}


	if ($resultCount === 0 ) {
		echo "<strong><h2>no events found</strong></h2>";
	}


} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to search events" . $exception->getMessage() . "</div>";

}
?>