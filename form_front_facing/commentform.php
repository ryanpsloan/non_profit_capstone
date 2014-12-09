
<?php
session_start();
require_once("../php/form/csrf.php");
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

		if($userEvent->commentPermission === 2 || $userTeam->commentPermission === 2 || $teamEvent->commentPermission === 2){
		echo "<p>You are not permitted to comment.</p>";}
		elseif($userEvent->commentPermission === 1 || $userTeam->commentPermission === 1 || $teamEvent->commentPermission === 1){
		echo <<<EOF
			<form id="commentForm" action="../php/form/commentprocessor.php" method="POST">
				<?php echo generateInputTags();?>
				<label for="commentBox">Type your comment:</label>
				<br>
				<textarea class="commentBox" name="comment" maxlength="1024" rows="6" cols="24"></textarea>
				<br>
				<input type="submit" value="Submit">

			</form>
EOF;	}
		?>
		</body>
	</html>