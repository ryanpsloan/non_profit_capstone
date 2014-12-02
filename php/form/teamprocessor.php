<?php
/**
 * Processor for the team form of the helpabq site
 * User: Cass
 *
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("team.php");

try {
	// verify the form was submitted OK
	if(@isset($_POST["teamId"]) === false || @isset($_POST["teamName"]) === false || @isset($_POST["teamCause"]) === false) {
		throw(new RuntimeException("Form variables incomplete or missing"));
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	// create a new object and insert it to mySQL
	$authToken = bin2hex(openssl_random_pseudo_bytes(16));
	$cause = new Cause(null, $_POST["teamId"], $_POST["teamName"], $_POST["teamDescription"]);
	$mysqli = MysqliConfiguration::getMysqli();
	$cause->insert($mysqli);
	}catch(Exception $exception) {
		echo "Unable to create a new team: " . $exception->getMessage();
}
?>