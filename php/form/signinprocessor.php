<?php
/**
 * Signin form processor for the helpabq website
 * User: Martin
 */


session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../user.php");
require_once("../profile.php");
?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="signUp.js"></script>
	<link type="text/css" rel="stylesheet" href="sign.css"/>
	<link type="text/css" rel="stylesheet" href="../index1.css"/>


	<title>SignUp Form</title>
</head>
<body>
<div class="container">

<?php
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
		throw(new RuntimeException("User does not exist please check userName/email or signup"));
	}

	$profile = Profile::getProfileByUserId($mysqli, $user->getUserId());

	$_SESSION["userId"] = $user->getUserId();
	$_SESSION["userName"] = $user->getUserName();
	$_SESSION["email"] = $user->getEmail();
	$_SESSION["authToken"] = $user->getAuthToken();
	$_SESSION["permissions"] = $user->getPermissions();
	$_SESSION["profileId"] = $profile->getProfileId();



   echo "<div class=\"alert alert-info\" role=\"alert\"><strong>Welcome to HelpAbq.com</strong>  </div>";

} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to sign in: " . $exception->getMessage() . "</div>";

	}
?>

</div>
</body>
</html>