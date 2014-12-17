<!--* join cause form processor-->
<!--* User: Martin-->
<?php
	session_start();
	require_once("/etc/apache2/capstone-mysql/helpabq.php");
	require_once("csrf.php");
	require_once("../../form_front_facing/navbar.php");
	require_once("../profile.php");
	require_once("../user.php");
	require_once("../usercause.php");
	require_once("../cause.php");
?>

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="refresh" content="5;../../index.php" />
	<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../../form_front_facing/searchCause.js"></script>
	<link type="text/css" rel="stylesheet" href="../../form_front_facing/sign.css"/>
	<link type="text/css" rel="stylesheet" href="../../index1.css"/>

	<title>Join Cause Form</title>
</head>
<body>
<?php navBarProcessor() ?>
	<div class="container">
<?php

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

</div>
</body>
</html>
