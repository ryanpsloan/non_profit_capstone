
<?php ?>
<!DOCTYPE html>
	<html>
		<head lang="en">
			<meta charset="UTF-8">
			<title>Comment</title>
		</head>
		<body>
			<form id="commentForm" action="commentprocessor.php" method="POST">
				<label for="commentText">Type your comment:</label>
				<input type="text" class="commentBox" name="comment" maxlength="1024">
				<input type="datetime" name="date" value=<?php $date=date_create_from_format("Y-m-d H:i:s", "");?>>
				<input type="submit" value="Submit">
			</form>
		</body>
	</html>