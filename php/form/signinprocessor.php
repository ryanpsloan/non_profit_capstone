<?php
/**
 * Signin form processor for the helpabq website
 * User: Martin
 */


session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../user.php");


try {
	//verify the form was submitted properly

		if(@isset($_POST["userName"]) === false || @isset($_POST["passwordHash"]) === false) {
			throw(new RuntimeException("Please enter UserName or email or password."));
		}
		// verify the CSRF tokens
		if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
			throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));

		}
	//create a new object and insert it to mySQL
	$mysqli    = MysqliConfiguration::getMysqli();
	$user = User::getUserByUserName($mysqli, $_POST["userName"]);
	if ($user===null){
		$user = User::getUserByEmail($mysqli,$_POST["userName"]);
	}
	if ($user===null){
		throw(new RuntimeException("User is null and cannot be null"));
	}
	$_SESSION["userObj"] = $user;

   echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Welcome to HelpAbq.com</strong>  </div>";

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to sign in: " . $exception->getMessage() . "</div>";

	}