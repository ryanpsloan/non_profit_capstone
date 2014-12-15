<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../userevent.php");

try{
	$mysqli = MysqliConfiguration::getMysqli();
	if(($mysqli = MysqliConfiguration::getMysqli()) === false){
		throw(new mysqli_sql_exception("Server connection failed, please try again later."));
	}

	if(@isset($_POST['eventId']) === false || @isset($_POST['userEventRole'])
			=== false ||@isset ($_POST['profileId']) === false || @isset($_POST['commentPermission']) === false
						|| @isset($_POST['banStatus']) === false) {
		throw(new UnexpectedValueException("The permission parameters are invalid please try again."));
	}

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	$eventId = $_POST['eventId'];
	$eventId = intval($eventId);

	$profileId = $_POST['profileId'];
	$profileId = intval($profileId);

	$userEventRole = $_POST['userEventRole'];
	$userEventRole = intval($userEventRole);

	$commentPermission = $_POST['commentPermission'];
	$commentPermission = intval($commentPermission);

	$banStatus = $_POST['banStatus'];
	$banStatus = intval($banStatus);

	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	$permissionsUpdate = UserEvent::getUserEventByProfileEventId($mysqli, $profileId, $eventId);
	$permissionsUpdate[0][0]->setUserEventRole($userEventRole);
	$permissionsUpdate[0][0]->setCommentPermission($commentPermission);
	$permissionsUpdate[0][0]->setBanStatus($banStatus);

	$permissionsUpdate[0][0]->update($mysqli);

}catch (Exception $exception){
	echo "We have encountered an error." . " " . $exception->getMessage();
}