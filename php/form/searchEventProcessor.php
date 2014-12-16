
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
	<script type="text/javascript" src="searchEvent.js"></script>
	<link type="text/css" rel="stylesheet" href="sign.css"/>

	<title>Join Cause Form</title>
</head>
<body>
<div class="container">


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

		echo "<form id='goToEvent' action='../../form_front_facing/eventpage.php' method='post'>"
				. generateInputTags() .
			  "<input type='hidden' name='eventId' value='" . $event->eventId . "'>
			   <button type='submit' class='btn btn-primary'>Go To Event Page</button>
			   </form>";


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

</div>
</body>
</html>