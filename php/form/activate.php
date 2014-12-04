<?php

/**
 * Signup form processor for the helpabq website
 * User: Martin
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../profile.php");
require_once("../user.php");

try {

	$mysqli = MysqliConfiguration::getMysqli();
	echo "<p>Authenticating your account</p>";
	$authToken = $_GET['authToken'];
	$newUser = User::getUserByAuthToken($mysqli, $authToken);
	$newProfile = Profile::getProfileByProfileId($mysqli, $profileId);

	$_SESSION['userId'] = $newUser->getUserId();
	$_SESSION['profileId'] = $newProfile;

	echo "<div class='alert alert-success' role='alert'> <a href='#' class='alert-link'>Your account has been authenticated. You are now signed in</a>
			</div><p><a href='../index.php'>Home</a></p>";
}catch(Exception $e){
	echo "<div class='alert alert-danger' role='alert'><a href='#' class='alert-link'>".$e->getMessage()."</a>
	</div>";
}
?>
?>
