<?php
/**
 * Signup HTML form
 * User: Martin
 *
 */
session_start();
require_once("../php/form/csrf.php");
require_once("../php/form/functions.php");
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
	<script type="text/javascript" src="signUp.js"></script>
	<link type="text/css" rel="stylesheet" href="sign.css"/>
	<link type="text/css" rel="stylesheet" href="../index1.css"/>


	<title>SignUp Form</title>
</head>
<body>
<?php navBarForm() ?>
<div class="container">
	<div id="outputArea"></div>
		<form id="signUp" class="form-signin" action="../php/form/signupprocessor.php" method="POST">
		<?php echo generateInputTags();?>
			<h2 class="form-signin-heading">Please sign up</h2>
			<label for="userName">username *</label>
			<br>
			<input type="text" class="form-control" id="userName" name="userName" autocomplete="off">
			<br>
			<label for="email">email *</label>
			<br>
			<input type="text" class="form-control" id="email" name="email" autocomplete="off">
			<br>
			<label for="password">Password *</label>
			<br>
			<input type="password" class="form-control" id="password" name="password" autocomplete="off">
			<br>
			<label for="confPassword">Confirm Password *:</label>
			<br>
			<input type="password" class="form-control" id="confPassword" name="confPassword" autocomplete="off">
			<br>
			<label for="userTitle">Title </label>
			<br>
			<input type="text" class="form-control" id="userTitle" name="userTitle" autocomplete="off">
			<br>
			<label for="firstName">First Name *</label>
			<br>
			<input type="text" class="form-control" id="firstName" name="firstName" autocomplete="off">
			<br>
			<label for="midInit">Middle Initial</label>
			<br>
			<input type="text" class="form-control" id="midInit" name="midInit" autocomplete="off">
			<br>
			<label for="lastName">Last Name *</label>
			<br>
			<input type="text" class="form-control" id="lastName" name="lastName" autocomplete="off">
			<br>
			<label for="bio">Biography 1000 Characters Max:</label>
			<br>
			<textarea class="bio" class="form-control" name="biography" maxlength="1024" rows="15" cols="80"></textarea>
			<br>
			<label for="attention">Attention</label>
			<br>
			<input type="text" class="form-control" id="attention" name="attention" autocomplete="off">
			<br>
			<label for="street1">Address 1</label>
			<br>
			<input type="text" class="form-control" id="street1" name="street1" autocomplete="off">
			<br>
			<label for="street2">Address 2</label>
			<br>
			<input type="text" class="form-control" id="street2" name="street2" autocomplete="off">
			<br>
			<label for="city">City</label>
			<br>
			<input type="text" class="form-control" id="city" name="city" autocomplete="off">
			<br>
			<label for="state">State</label>
			<br>
			<input type="text" class="form-control" id="state" name="state" autocomplete="off">
			<br>
			<label for="zipCode">Zip Code *</label>
			<br>
			<input type="text" class="form-control" id="zipCode" name="zipCode" autocomplete="off">
			<br>
				<h4>The fields denoted with * cannot be left blank</h4>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
		</form>



</body>
</html>