<?php
/**
 * Signup form processor for the helpabq website
 * User: Martin
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("profile.php");
require_once("user.php");

try{
		//verify the form was submitted properly
		if (@isset($_POST["userName"]) === false || @isset($_POST["email"]) === false || @isset($_POST["passwordHash"]) === false ||
	  		@isset($_POST["firstName"]) === false || @isset($_POST["lastName"]) === false || @isset($_POST["zipCode"]) === false) {
			throw(new RuntimeException("Form variables incomplete or missing"));
		}
		// verify the CSRF tokens
		if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
			throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
		}

		//create a new object and insert it to mySQL


		$mysqli    = MysqliConfiguration::getMysqli();
		$signupUser = new User(null,$_POST["userName"], $_POST["email"], $passwordHash, $salt, $authToken, 0);
		$signupUser->insert($mysqli);
		$signupProfile = new Profile(null, $signupUser->getUserId(), $_POST["userTitle"], $_POST["firstName"],$_POST["midInit"], $_POST["lastName"],
											  $_POST["bio"], $_POST["attention"],$_POST["street1"], $_POST["street2"], $_POST["city"],
											  $_POST["state"], $_POST["zipCode"]);
		$signupProfile->insert($mysqli);



	} catch(Exception $exception){
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to sign up: " . $exception->getMessage() . "</div>";

		}

?>