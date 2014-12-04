<?php
/**
 * Signup HTML form
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
	<script type="text/javascript" src="SignUp.js"></script>

	<title>SignUp Form</title>
</head>
<body>
<div id="outputArea"></div>
<form id="signUp" action="../php/form/signupprocessor.php" method="POST">
	<?php echo generateInputTags();?>
	<label for="userName">username *</label>
	<br>
	<input type="text" id="userName" name="userName" autocomplete="off">
	<br>
	<label for="email">email *</label>
	<br>
	<input type="text" id="email" name="email" autocomplete="off">
	<br>
	<label for="password">Password *:</label>
	<br>
	<input type="password" id="password" name="password" autocomplete="off">
	<br>
	<label for="confPassword">Confirm Password *:</label>
	<br>
	<input type="password" id="confPassword" name="confPassword" autocomplete="off">
	<br>
	<label for="userTitle">Title </label>
	<br>
	<input type="text" id="userTitle" name="userTitle" autocomplete="off">
	<br>
	<label for="firstName">First Name *</label>
	<br>
	<input type="text" id="firstName" name="firstName" autocomplete="off">
	<br>
	<label for="midInit">Middle Initial</label>
	<br>
	<input type="text" id="midInit" name="midInit" autocomplete="off">
	<br>
	<label for="lastName">Last Name *</label>
	<br>
	<input type="text" id="lastName" name="lastName" autocomplete="off">
	<br>
	<label for="bio">Biography 1000 Characters Max:</label>
	<br>
	<textarea class="bio" name="biography" maxlength="1024" rows="15" cols="80"></textarea>
	<br>
	<label for="attention">Attention</label>
	<br>
	<input type="text" id="attention" name="attention" autocomplete="off">
	<br>
	<label for="street1">Address 1</label>
	<br>
	<input type="text" id="street1" name="street1" autocomplete="off">
	<br>
	<label for="street2">Address 2</label>
	<br>
	<input type="text" id="street2" name="street2" autocomplete="off">
	<br>
	<label for="city">City</label>
	<br>
	<input type="text" id="city" name="city" autocomplete="off">
	<br>
	<label for="state">State</label>
	<br>
	<input type="text" id="state" name="state" autocomplete="off">
	<br>
	<label for="zipCode">Zip Code *</label>
	<br>
	<input type="text" id="zipCode" name="zipCode" autocomplete="off">
	<br>
	<h4>The fields denoted with * cannot be left blank</h4>
	<input type="submit" value="Sign Up">

</form>



</body>
</html>