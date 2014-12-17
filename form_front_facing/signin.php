<?php
/**
 * Signin HTML form
 * User: Martin
 *
 */
session_start();
require_once("../php/form/csrf.php");
require_once("navbar.php");
require_once("../php/profile.php");
require_once("../php/user.php");
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
	<link type="text/css" rel="stylesheet" href="sign.css"/>
	<link type="text/css" rel="stylesheet" href="../index1.css"/>

	<title>SignIn Form</title>
</head>
<body>
	<?php navBarForm() ?>
		<div class="container">
			<div id="outputArea"></div>
				<form id="signIn" class="form-signin" action="../php/form/signinprocessor.php" method="POST">
				<?php echo generateInputTags();?>
					<h2 class="form-signin-heading">Please sign in</h2>
					<label for="userName">username/email</label>
					<br>
					<input type="text" class="form-control" id="userName" name="userName" autocomplete="off">
					<br>
					<label for="passwordHash">Password:</label>
					<br>
					<input type="password" id="passwordHash" class="form-control" name="passwordHash" autocomplete="off">
					<br>
					<button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>


					<div id="signUpLink"><p>OR</p>
					<a href="signUp.php">Sign Up</a></div>
				</form>
		</div>
</body>
</html>