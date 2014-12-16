<?php
/**
 * join cause form processor
 * User: Martin
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../usercause.php");
require_once("../cause.php");


// verify CSRF
try {
	if(@isset($_POST["causeId"]) === false) {
		throw(new RuntimeException("Please enter causeName"));
	}

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}
// create a UserTeam object with the profileId and causeId
	$mysqli    	= MysqliConfiguration::getMysqli();
	$cause 		= Cause::getCauseByCauseId($mysqli, $_POST["causeId"]);
			if ($cause === null) {
				throw (new UnexpectedValueException ("No Cause found"));
			}

	$joinUserCause = new UserCause($_SESSION["profileId"],$cause->getCauseId());


// insert into mySQL
	$joinUserCause->insert($mysqli);

	echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Thank you for supporting cause: " . $cause->getCauseName() . "</strong>  </div>";

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to join cause: " . $exception->getMessage() . "</div>";

}

?>