
<?php
require_once("../php/form/csrf.php");
session_start();
?>
<!DOCTYPE html>
	<html>
		<head lang="en">
			<meta charset="UTF-8">
			<title>Comment</title>
		</head>
		<body>
			<form id="commentForm" action="../php/form/commentprocessor.php" method="POST">
				<?php echo generateInputTags();?>
				<label for="commentBox">Type your comment:</label>
				<br>
				<textarea class="commentBox" name="comment" maxlength="1024" rows="6" cols="24"></textarea>
				<br>
				<input type="submit" value="Submit">
			</form>
		</body>
	</html>