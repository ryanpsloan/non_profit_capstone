<?php

/**
 * Signup form processor for the helpabq website
 * User: Martin
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");// verify the CSRF tokens
if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
	throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
}
require_once("../profile.php");
require_once("../user.php");

try {

	$mysqli = MysqliConfiguration::getMysqli();



	echo "<p>Authenticating your account</p>";
	$authToken = $_GET['authToken'];
	$newUser = User::getUserByAuthToken($mysqli, $authToken);
	$newProfile = Profile::getProfileByUserId($mysqli, $newUser->getUserId());

	$_SESSION["userId"] = $newUser->getUserId();
	$_SESSION["userName"] = $newUser->getUserName();
	$_SESSION["permissions"] = $newUser->getPermissions();
	$_SESSION["firstName"]= $newProfile->getFirstName();
	$_SESSION["lastName"]= $newProfile->getLastName();
	$_SESSION["zipCode"]= $newProfile->getZipCode();


	echo "<div class='alert alert-success' role='alert'> <a href='#' class='alert-link'>Your account has been authenticated. You are now signed in</a>
			</div><p><a href='../index.php'>Home</a></p>";
} catch(Exception $exception){
	echo "<div class='alert alert-danger' role='alert'><a href='#' class='alert-link'>".$exception->getMessage()."</a>
	</div>";
}
?>

