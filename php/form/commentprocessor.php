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
include("../comment.php");
include("../commentUser.php");
include("../commentEvent.php");
include("../commentTeam.php");

try {
	$mysqli = MysqliConfiguration::getMysqli();
	if(($mysqli = MysqliConfiguration::getMysqli()) === false){
		throw(new mysqli_sql_exception("Server connection failed, please try again later."));
	}

	if(@isset($_POST['comment']) === false) {
		throw(new UnexpectedValueException("The comment was blank, please try again."));
	}

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
	}

	$newComment = new Comment(null, $_POST["comment"], new DateTime());
	$newComment->insert($mysqli);
	echo "<p>Comment posted!</p>";

	//FIXME session is broken
	$newUserComment = new CommentUser($_SESSION["profileId"], $newComment->getCommentId());
	$mysqli->insert($newComment);

	if($_POST["eventId"] !== null) {
		$newEventComment = new CommentEvent ($_POST["eventId"], $newComment->getCommentId());
		$mysqli->insert($newEventComment);
	} elseif($_POST["teamId"] !== null) {
		$newTeamComment = new CommentTeam($_POST["teamId"], $newComment->getCommentId());
		$mysqli->insert($newTeamComment);
	} else {
		throw(new UnexpectedValueException("Unable to post comment"));
	}

} catch (RuntimeException $exception){
		echo "We have encountered an error." . " " . $exception->getMessage();
}
