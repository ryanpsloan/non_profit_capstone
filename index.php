<?php
	session_start();
	require_once("php/user.php");
	require_once("php/profile.php");
	require_once("form_front_facing/navbar.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head lang="en">
		<title>Help Across ABQ</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="index1.css">
	<style>

		h2 {
			color: #337ab7;
			text-align: center;
		}

		body {
			background-color: #ffffff ;
		}
	</style>

</head>
<body>
	<?php navBar() ?>
							<!-- Main jumbotron on why to volunteer -->
	<div class="container">

		<div class="jumbotron">
			<h2>VOLUNTEER! @ HELP ACROSS ABQ</h2>
			<p>Help Across ABQ is a site that brings the caring people of Albuquerque together with organizations and events to better our community.  Plenty of reasons exist  for why you would volunteer.</p>
			<p>Which of these applies to you?</p>
			<p>to make a difference --	to feel needed -- to share a skill -- to get to know a community -- to demonstrate commitment -- to a cause/belief -- to do your civic duty --
				satisfaction from accomplishment -- to keep busy -- to have an impact -- to learn something new -- to feel proud -- to make new friends -- to help someone			for fun!				to assure progress
				to feel good -- to be part of a team -- to be an agent of change</p>
			<a style="color: white" href="form_front_facing/SignUp.php"><button class="col-lg-12 btn btn-primary">Sign Up Today!</a></button>
		</div>
	</div>

</body>
</html>

