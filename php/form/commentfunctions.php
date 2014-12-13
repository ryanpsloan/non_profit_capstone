<?php
require_once("../php/commentEvent.php");
require_once("../php/commentUser.php");
require_once("../php/profile.php");
require_once("../php/comment.php");


function displayEventComment($pageId){
	$mysqli = $mysqli = MysqliConfiguration::getMysqli();
	$commentIds = array();
	$userComments = array();
	$comments = array();
	$profileIds = array();
	$profiles = array();
	$eventComment = commentEvent::getCommentEventByEventId($mysqli, $pageId);

	for($i = 0; $i<count($eventComment); $i++) {
		$commentIds[] = $eventComment[$i]->commentId;

		$userComments[] = CommentUser::getCommentUserByCommentId($mysqli, $commentIds[$i]);

		$comments = Comment::getCommentByCommentId($mysqli, $commentIds[$i]);

		$dateString = $comments->commentDate->format("Y-m-d H:i:s");

		$profileIds[] = $userComments[$i][0]->getProfileId();

		$profiles[] = Profile::getProfileByProfileId($mysqli, $profileIds[$i]);

		$profileNames[] = $profiles[$i]->getFirstName() . " " . $profiles[$i]->getLastName();

		echo "<p>$profileNames[$i]</p><br/>
				<p>$dateString</p><br/>
				<p>$comments->commentText</p>";
	}

	function displayTeamComment($pageId){
		$mysqli = $mysqli = MysqliConfiguration::getMysqli();
		$commentIds = array();
		$userComments = array();
		$profileIds = array();
		$profiles = array();
		$teamComment = commentTeam::getCommentTeamByTeamId($mysqli, $pageId);

		for($i = 0; $i<count($teamComment); $i++){
			$commentIds[] = $teamComment[$i]->commentId;

			$userComments[] = CommentUser::getCommentUserByCommentId($mysqli, $commentIds[$i]);

			$comments = Comment::getCommentByCommentId($mysqli, $commentIds[$i]);

			$dateString = $comments->commentDate->format("Y-m-d H:i:s");

			$profileIds[] = $userComments[$i][0]->getProfileId();

			$profiles[] = Profile::getProfileByProfileId($mysqli, $profileIds[$i]);

			$profileNames[] = $profiles[$i]->getFirstName() . " " . $profiles[$i]->getLastName();

			echo "<p>$profileNames[$i]</p><br/>
					<p>$dateString</p><br/>
					<p>$comments->commentText</p>";
		}
	}


}

?>