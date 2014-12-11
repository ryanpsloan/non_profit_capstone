<?php
/**
 * Create team search processor
 * User: Martin
 */

session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../team.php");
$mysqli    = MysqliConfiguration::getMysqli();

try {
	//verify the form was submitted properly

	if(@isset($_POST["teamName"]) === false) {
		throw(new RuntimeException("Please enter a team to search by."));
	}

	// use filter_input to sanitize event name
	$teamName = (filter_input(INPUT_POST, "teamName", FILTER_SANITIZE_STRING));

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));

	}

	// grab mysql data
	$teams = Team::getTeamByTeamName($mysqli, $teamName);
	/**
	 * return the results to the user
	 */
	$resultCount = count($teams);
	for($i = 0; $i < $resultCount; $i++)	{
		$team  = $teams[$i];

		echo "<strong>" . $team->getTeamName() . "</strong><br/>" .
			$team->getTeamCause()	.	"<br/>";


	echo "<form id=\"joinTeam\" action=\"joinTeamProcessor.php\" method=\"POST\">";
	echo generateInputTags();
	echo "<input type = 'hidden' name = 'teamId' value = \"" . $team->getTeamId() . "\">
		<input id = \"profileSubmit\" type=\"submit\" value=\"Join Team\">
		</form>";

	}


} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to search events" . $exception->getMessage() . "</div>";

}
?>