<?php
		function commentForm($pageType, $pageId){


			// NOTICE: array returns one result so no need to loop comment.
			// TODO: look into isset for comment

			if($pageType === 1) {
				$mysqli = MysqliConfiguration::getMysqli();
				$userTeam = UserTeam::getUserTeamByProfileTeamId($mysqli, $_SESSION["profileId"], $pageId);
				$permissionCheck = $userTeam[0][0]->getCommentPermission();
			} elseif($pageType === 2) {
				$mysqli = MysqliConfiguration::getMysqli();
				$teamEvent = TeamEvent::getTeamEventByTeamEventId($mysqli, $_POST["teamId"], $pageId);
				$permissionCheck = $teamEvent[0][0]->getCommentPermission();
			} elseif($pageType === 3) {
				$mysqli = MysqliConfiguration::getMysqli();
				$userEvent = UserEvent::getUserEventByProfileEventId($mysqli, $_SESSION["profileId"], $pageId);
				$permissionCheck = $userEvent[0][0]->commentPermission;
			} else {
				$permissionCheck = null;
			}
			//Generic comment form to be inserted into various pages
			$form = "

				<label for=\"commentBox\">Type your comment:</label>
				<br>
				<textarea class=\"commentBox\" name=\"comment\" maxlength=\"1024\" rows=\"6\" cols=\"24\"></textarea>
				<input type='hidden' name='pageId' value='$pageId'>
				<input type='hidden' name='pageType' value='$pageType'>
				<br>
				<input type=\"submit\" value=\"Submit\">
			</form>
			</div>
";
			if($permissionCheck === null) {
				echo "<p>You are not permitted to comment. Please sign in.</p>";
			} elseif($permissionCheck === 2) {
				echo "<p>You are not permitted to comment.</p>";
			} elseif($permissionCheck === 1) {
				echo $form;
			} else {
				echo "<p>You are not permitted to comment.</p>.</p>";
			}
		}
?>