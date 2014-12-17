<?php
/**
 * Signup form processor for the helpabq website
 * User: Martin
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../../form_front_facing/navbar.php");
require_once("../profile.php");
require_once("../user.php");
?>

<html>
<head lang="en">
	<meta charset="UTF-8" />
	<meta http-equiv="refresh" content="5; url=../../index.php"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../../form_front_facing/searchEvent.js"></script>
	<link type="text/css" rel="stylesheet" href="../../form_front_facing/sign.css"/>
	<link type="text/css" rel="stylesheet" href="../../index1.css"/>

	<title>Activation</title>
</head>
<body>
<?php navBarProcessor() ?>
	<div class="container">
		<?php
		try {

			$mysqli = MysqliConfiguration::getMysqli();



			echo "<p>Authenticating your account</p>";
			$authToken = $_GET['authToken'];
			$newUser = User::getUserByAuthToken($mysqli, $authToken);
			$newProfile = Profile::getProfileByUserId($mysqli, $newUser->getUserId());

			$_SESSION["userId"] = $newUser->getUserId();
			$_SESSION["userName"] = $newUser->getUserName();
			$_SESSION["permissions"] = $newUser->getPermissions();
			$_SESSION["profileId"] = $newProfile->getProfileId();
			$_SESSION["firstName"]= $newProfile->getFirstName();
			$_SESSION["lastName"]= $newProfile->getLastName();
			$_SESSION["zipCode"]= $newProfile->getZipCode();


			echo "<div class='alert alert-success' role='alert'> <a href='#' class='alert-link'>Your account has been authenticated. You are now signed in</a>
					</div><p><a href='../../index.php'>Home</a></p>";
		} catch(Exception $exception){
			echo "<div class='alert alert-danger' role='alert'><a href='#' class='alert-link'>".$exception->getMessage()."</a>
			</div>";
		}
		?>
	</div>
</body>
</html>
