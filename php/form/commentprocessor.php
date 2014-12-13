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

	$newUserComment = new CommentUser($_SESSION["profileId"], $newComment->commentId);
	$newUserComment->insert($mysqli);
	var_dump($_POST['pageType']);
	if($_POST['pageType'] === "3") {
		$newEventComment = new CommentEvent ($_POST['pageId'], $newComment->commentId);
		$newEventComment->insert($mysqli);
	} elseif($_POST['pageType'] === "1") {
		$newTeamComment = new CommentTeam($_POST['pageId'], $newComment->commentId);
		$newTeamComment->insert($mysqli);
	} else {
		throw(new UnexpectedValueException("Unable to post comment"));
	}

} catch (RuntimeException $exception){
		echo "We have encountered an error." . " " . $exception->getMessage();
}
