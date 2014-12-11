<?php
/**
 * join team form processor
 * User: Martin
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../userTeam.php");
require_once("../profile.php");
require_once("../team.php");
$mysqli    = MysqliConfiguration::getMysqli();


// verify CSRF
try {
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}
// create a UserTeam object with the profileId and teamId


	$joinUserTeam = new UserTeam($_SESSION, null, 1, 1, 1, 1, 1);

// insert into mySQL
	$joinUserTeam->insert($mysqli);
} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to sign in: " . $exception->getMessage() . "</div>";

}

?>