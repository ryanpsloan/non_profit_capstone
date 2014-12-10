
<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/form/csrf.php");
require_once("../php/userTeam.php")
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
			<title>Comment</title>
		</head>
		<body>
		<?php
		//FIXME post and session broken
		$mysqli = MysqliConfiguration::getMysqli();
		function comment()
		{
			$pageId = $_POST("pageId");
			$pageType = $_POST("pageType");
			$userTeam = UserTeam::getUserTeamByProfileTeamId($mysqli, $_SESSION("profileId"), $pageId);
			$teamEvent = TeamEvent::getTeamEventByTeamEventId($mysqli, $_SESSION("teamId"), $pageId);
			$userEvent = UserEvent::getUserEventByProfileEventId($mysqli, $_SESSION("profileId"), $pageId);
			// NOTICE: array returns one result so no need to loop comment.
			// TODO: look into isset for comment

			if(@isset($pageType) === 1) {
				$permissionCheck = $userTeam[0][0]->getCommentPermission();
			} elseif(@isset($pageType) === 2) {
				$permissionCheck = $teamEvent[0][0]->getCommentPermission();
			} elseif(@isset($pageType) === 3) {
				$permissionCheck = $userEvent[0][0]->commentPermission;
			} else {
				$permissionCheck = null;
			}

			//Generic comment form to be inserted into various pages
			$form = <<<EOF
			<form id="commentForm" action="../php/form/commentprocessor.php" method="POST">
				<?php echo generateInputTags();?>
				<label for="commentBox">Type your comment:</label>
				<br>
				<textarea class="commentBox" name="comment" maxlength="1024" rows="6" cols="24"></textarea>
				<br>
				<input type="submit" value="Submit">
			</form>
EOF;
			var_dump($userTeam);
			if($permissionCheck === null) {
				echo "<p>You are not permitted to comment.</p>";
			} elseif($permissionCheck === 2) {
				echo "<p>You are not permitted to comment.</p>";
			} elseif($permissionCheck === 1) {
				echo $form;
			} else {
				echo "<p>You are not permitted to comment.</p>.</p>";
			}
		}
		?>

		</body>
	</html>