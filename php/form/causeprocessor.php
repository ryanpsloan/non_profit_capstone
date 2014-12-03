<?php
/**
 *Processor for the cause form of the helpabq site
 * User: Cass
 *
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../cause.php");

try {
	// verify the form was submitted OK
	if(@isset($_POST["causeName"]) === false || @isset($_POST["causeDescription"]) === false) {
		throw(new RuntimeException("Form variables incomplete or missing"));
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	// create a new object and insert it to mySQL
	$cause = new Cause(null, $_POST["causeName"], $_POST["causeDescription"]);
	$mysqli = MysqliConfiguration::getMysqli();
	$cause->insert($mysqli);

	echo"<div class='alert alert-success' role='alert'>Thank you for supporting this cause.</div>";

}catch(Exception $exception) {
	echo "Unable to create a new cause: " . $exception->getMessage() ;
}
?>