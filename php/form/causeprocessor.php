<!--*Processor for the cause form of the helpabq site-->
<!--* User: Cass-->

<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="refresh" content="5;../index.php" />
	<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../../form_front_facing/searchTeam.js"></script>
	<link type="text/css" rel="stylesheet" href="../../form_front_facing/sign.css"/>

	<title>Join Cause Form</title>
</head>
<body>
<div class="container">


<?php

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

	echo"<div class='alert alert-info' role='alert'>Thank you for supporting a cause to help ABQ.</div>";

}catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to create a new cause: " . $exception->getMessage() . "</div>";

}


?>

</div>
</body>
</html>