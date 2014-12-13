<?php
	session_start();
	require_once("/etc/apache2/capstone-mysql/helpabq.php");
	require_once("../php/form/csrf.php");
	require_once("../php/event.php");
	require_once("commentform.php");
	require_once("../php/userevent.php");
	require_once("../php/form/commentfunctions.php");
	$mysqli = $mysqli = MysqliConfiguration::getMysqli();
	$event = Event::getEventByEventId($mysqli, 1);
	$dateString = $event->eventDate->format("Y-m-d H:i:s");
	$pageId = $event->eventId;
	$pageType = 3;
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
	<title><?php echo $event->eventTitle?></title>
</head>
<body>
	<?php /*navBar()*/?>
	<aside>
		<?php
			echo "<h3>$event->eventTitle</h3>";
			echo "<h5>$dateString</h5>";
			echo "<h5>$event->eventLocation</h5>";
			// NOTICE In the future have the events, teams, and other classes have an identifying hash
			// NOTICE This is further increase security when it comes to malicious users chaning form data
			echo "
					<form action=\"../php/form/permissionsprocessor.php\" method='post'>
					";
			echo generateInputTags();

			echo "	<input type='hidden' name=\"permissionType\" value=\"3\">
						<input type='hidden' name=\"eventId\" value=\"$event->eventId\">
						<input type='submit' value='Edit Permissions'>
					</form>
					";
		?>
	</aside>
	<section>
		<div>
			<form id="commentForm" action="../php/form/commentprocessor.php" method="POST">
		<?php
				echo generateInputTags();
		commentForm($pageType, $pageId);
		?>
	</section>

	<section>
		<?php displayEventComment($pageId); ?>
	</section>


</body>