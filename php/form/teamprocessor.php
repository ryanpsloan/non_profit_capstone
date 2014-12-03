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
	$team = new Team(null, $_POST["teamName"], $_POST["teamCause"]);
	$mysqli = MysqliConfiguration::getMysqli();
	$team->insert($mysqli);

	echo"<div class='alert alert-success' role='alert'>Thank you for joining a team.</div>";


}catch(Exception $exception) {
	echo "Unable to create a new team: " . $exception->getMessage();
}
?>