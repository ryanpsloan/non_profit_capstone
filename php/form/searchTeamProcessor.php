
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

	<title>Join Team Form</title>
</head>
<body>
<div class="container">
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
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong><h1>Here are your results</strong></h1>  </div><br/> <br/>";
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

			echo "<form id='goToTeam' action='../../form_front_facing/teampage.php' method='get'>"
				. generateInputTags() .
				"<input type='hidden' name='teamId' value ='" . $team->getTeamId() . "' >
			 <button type='submit' class='btn btn primary'>Go To Team Page</button>
			 </form>";


			echo "<form id='joinTeam' action='joinTeamProcessor.php' method='post'>";
			echo generateInputTags();
			echo "<input type = 'hidden' name = 'teamId' value = '" . $team->getTeamId() . "'>
			<button type ='submit' class='btn btn-primary'>Join Team</button>
			</form>";

		}


		if ($resultCount === 0 ) {
			echo "<strong><h2>no teams found</strong></h2>";
		}


	} catch(Exception $exception) {
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to search teams" . $exception->getMessage() . "</div>";

	}
	?>


</div>
</body>
</html>

