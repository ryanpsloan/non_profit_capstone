<?php
/**
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

	session_start();
	require_once("/etc/apache2/capstone-mysql/helpabq.php");
	require_once("../php/form/csrf.php");
	require_once("../php/event.php");
	require_once("commentform.php");
	require_once("../php/userevent.php");
	require_once("../php/form/commentfunctions.php");
	$mysqli = $mysqli = MysqliConfiguration::getMysqli();
	$event = Event::getEventByEventId($mysqli, $_GET['eventId']);
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
	<script type="text/javascript" src="comment.js"></script>
	<link type="text/css" href="teameventpage.css" rel="stylesheet">
	<title><?php echo $event->eventTitle?></title>
</head>
<body>
	<?php /*navBar()*/?>
	<div class="container">
	<div id="outputArea"></div>
		<div class="row">
				<aside class="col-lg-4 information">
					<?php
						echo "<h3>$event->eventTitle</h3>";
						echo "<h5>$dateString</h5>";
						echo "<h5>$event->eventLocation</h5>";
						// NOTICE In the future have the events, teams, and other classes have an identifying hash
						// NOTICE This is to further increase security when it comes to malicious users chaning form data
						echo "
								<form action=\"../php/form/permissionsprocessor.php\" method='post'>
								";
						echo generateInputTags();

						echo "	<input type='hidden' name=\"permissionType\" value=\"3\">
									<input type='hidden' name=\"eventId\" value=\"$event->eventId\">
									<button type='submit' class='btn btn-primary'>Edit Permissions</button>
								</form>
								";
					?>
				</aside>
				<div id="mainContent" class=" col-lg-8">
					<section>
							<form id="commentForm" class="control-form" action="../php/form/commentprocessor.php" method="POST">
							<?php
							echo generateInputTags();
							commentForm($pageType, $pageId);
							?>
					</section>

					<section class="comments">
						<?php displayEventComment($pageId); ?>
					</section>
				</div>
			</div>
		</div>
	<div class="clearfix"></div>
</body>