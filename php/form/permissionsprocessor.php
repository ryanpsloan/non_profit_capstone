<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../event.php");
require_once("../team.php");
require_once("../comment.php");
require_once("../user.php");
require_once("../profile.php");
require_once("../teamevent.php");
require_once("../userevent.php");
require_once("../userTeam.php");
require_once("permissionfunction.php");
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
		<script type="text/javascript" src="signIn.js"></script>
		<link type="text/css" rel="stylesheet" href="../../index1.css"/>

		<title>Permissions</title>
	</head>
	<body>
	<?php navBarProcessor() ?>
		<div class="container">
		<?php
			try{
				$mysqli = MysqliConfiguration::getMysqli();
				if(($mysqli = MysqliConfiguration::getMysqli()) === false){
					throw(new mysqli_sql_exception("Server connection failed, please try again later."));
				}

				if(@isset($_POST['permissionType']) === false) {
					throw(new UnexpectedValueException("The not a valid permission set, please try again."));
				}

				// verify the CSRF tokens
				if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
					throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
				}

					if($_POST["permissionType"] === "1") {
						//Function to call in the permission changing code
						userTeamPermissions();
					} elseif($_POST["permissionType"] === "2"){
						//Function to call in the permission changing code
						teamEventPermissions();

					} elseif($_POST["permissionType"] === "3"){
						//Function to call in the permission changing code
						userEventPermission();

					} else {
						throw(new UnexpectedValueException("Not a valid permission parameter."));
					}

			} catch (Exception $exception){
				echo "There has been an error" . " " . $exception->getMessage();
			}
		?>
		</div>
	</body>
</html>