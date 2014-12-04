<?php
/**
 * Signup form processor for the helpabq website
 * User: Martin
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../profile.php");
require_once("../user.php");
require_once("Mail.php");

try{
		//verify the form was submitted properly
		if (@isset($_POST["userName"]) === false || @isset($_POST["email"]) === false || @isset($_POST["password"]) === false ||
			 @isset($_POST["confPassword"]) === false || @isset($_POST["firstName"]) === false || @isset($_POST["lastName"]) === false ||
			 @isset($_POST["zipCode"]) === false) {
			throw(new RuntimeException("Form variables incomplete or missing"));
		}

		//ensures that passwords are identical
		$password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
		$confPassword = filter_input(INPUT_POST, "confPassword", FILTER_SANITIZE_STRING);
		if($password !== $confPassword) {
			throw(new RuntimeException("Passwords do not match"));
		}
		else {
			if(User::getUserByEmail($mysqli, $_POST["email"])!== null);
				throw(new RuntimeException("Email already exist please try again"));
		}

		// verify the CSRF tokens
		if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
			throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
		}

		//create a new object and insert it to mySQL

		$salt      		= bin2hex(openssl_random_pseudo_bytes(32));
		$authToken	 	= bin2hex(openssl_random_pseudo_bytes(16));
		$passwordHash	= hash_pbkdf2("sha512", $_POST["password"], $salt, 2048, 128);


		$mysqli    = MysqliConfiguration::getMysqli();
		$signupUser = new User(null,$_POST["userName"], $_POST["email"], $passwordHash, $salt, $authToken, 1);
		$signupUser->insert($mysqli);
		$signupProfile = new Profile(null, $signupUser->getUserId(), $_POST["userTitle"], $_POST["firstName"],$_POST["midInit"], $_POST["lastName"],
											  $_POST["bio"], $_POST["attention"],$_POST["street1"], $_POST["street2"], $_POST["city"],
											  $_POST["state"], $_POST["zipCode"]);
		$signupProfile->insert($mysqli);

	// email the user with an activation message
	$to   = $newUser->getEmail();
	$from = "noreply@helpabq.com";

	// build headers
	$headers                 = array();
	$headers["To"]           = $to;
	$headers["From"]         = $from;
	$headers["Reply-To"]      = $from;
	$headers["Subject"]      = $signupProfile->getFirstName() . " " . $signupProfile->getLastName() . ",
		Activate your HelpAbq Login";
	$headers["MIME-Version"] = "1.0";
	$headers["Content-Type"] = "text/html; charset=UTF-8";

	// build message
	$pageName = end(explode("/", $_SERVER["PHP_SELF"]));
	$url      = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
	$url      = str_replace($pageName, "activate.php", $url);
	$url      = "$url?authToken=$authToken";
	$message = <<< EOF
<html>
    <body>
        <h1>Welcome to HelpAbq.com.  Together we can this make city right.</h1>
        <hr />
        <p>Thank you for signing up to HelpAbq.com and joining us as we work together to make Albuquerque a better place
        for all. Visit the following URL to complete your registration process: <a href="$url">$url</a>.</p>
    </body>
</html>
EOF;

	// send the email
	error_reporting(E_ALL & ~E_STRICT);
	$mailer =& Mail::factory("sendmail");
	$status = $mailer->send($to, $headers, $message);
	if(PEAR::isError($status) === true)
	{
		echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to send mail message:" . $status->getMessage() . "</div>";
	}
	else
	{
		echo "<div class=\"alert alert-success\" role=\"alert\"><strong>Sign up successful!</strong> Please check your Email to complete the signup process.</div>";
	}

} catch(Exception $exception){
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to sign up: " . $exception->getMessage() . "</div>";

		}

?>