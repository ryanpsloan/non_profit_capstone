<?php
/**
 * Join Team HTML form
 * User: Martin
 *
 */
require_once("../php/form/csrf.php");
session_start();
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
<!--	<script type="text/javascript" src="signIn.js"></script>-->

	<title>Join Team Form</title>
</head>
<body>
<div id="outputArea"></div>
<form id="joinTeam" action="../php/form/joinTeamProcessor.php" method="POST">
	<?php echo generateInputTags();?>
	<input type = 'hidden' name = 'teamId' value = "Get TeamId">
	<input id = "profileSubmit" type="submit" value="Join Team">

</form>
</body>
</html><?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 12/10/2014
 * Time: 9:32 AM
 */ 