<?php
/**
 * This will function as the processor for the form commentform.php
 *	This will insert all the data from the commentform.php into mysql
 *
 * @author Dameon Smith <dameonsmith76@gamil.com>
 */
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
$mysqli = MysqliConfiguration::getMysqli();
include("../php/comment.php");

	if(@isset($_POST['comment'])=== false || @isset($_POST['date'])){
		echo "<p>Form variables incomplete or missing. Please refill form</p>";
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	$comment = 	filter_input(INPUT_POST, "comment", FILTER_SANITIZE_STRING);
	$date = 		filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING);

	$newComment = new Comment(null, $comment, $date);
	$newComment->insert($mysqli);
	echo"<p>Comment posted!</p>";
	var_dump($newComment);


