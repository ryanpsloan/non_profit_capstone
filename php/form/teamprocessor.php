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

	$joinUserTeam = new UserTeam($_SESSION["profileId"],$team->getTeamId(), 1, 1, 1, 1, 1);
	$joinUserTeam->insert($mysqli);


	echo"<div class='alert alert-success' role='alert'>Thank you for creating team:" . $team->getTeamName()." </div>";


}catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to create a new team: " . $exception->getMessage() . "</div>";

}