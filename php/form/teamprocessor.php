<?php
/**
 * Processor for the team form of the helpabq site
 * User: Cass
 *
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../team.php");
require_once("../userTeam.php");
require_once("../../form_front_facing/navbar.php");
require_once("../profile.php");
require_once("../user.php");
?>
	<!DOCTYPE html>
	<html>
	<head lang="en">
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="refresh" content="5;../index.php" />
		<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
		<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
		<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../../form_front_facing/team.js"></script>
		<link type="text/css" rel="stylesheet" href="../../index1.css"/>

		<title>Team Creation</title>
	</head>
	<body>
	<div class="container">
		<?php
		try {
			// verify the form was submitted OK
			if(@isset($_POST["teamName"]) === false || @isset($_POST["teamCause"]) === false) {
				throw(new RuntimeException("Form variables incomplete or missing"));
			}

			// verify the CSRF tokens
			if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
				throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
			}

			// create a new object and insert it to mySQL
			$mysqli = MysqliConfiguration::getMysqli();

			$team = new Team(null, $_POST["teamName"], $_POST["teamCause"]);
			$team->insert($mysqli);

			@$joinUserTeam = new UserTeam($_SESSION["profileId"],$team->getTeamId(), 3, 2, 1, 1, 1);
			$joinUserTeam->insert($mysqli);


			echo"<div class='alert alert-info' role='alert'>Thank you for creating team:" . $team->getTeamName()." </div>";


		}catch(Exception $exception) {
			echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to create a new team: " . $exception->getMessage() . "</div>";

		}
		?>
	</div>
	</body>
</html>