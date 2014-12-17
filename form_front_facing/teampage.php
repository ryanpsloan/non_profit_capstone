<?php
	session_start();
	require_once("/etc/apache2/capstone-mysql/helpabq.php");
	require_once("../php/form/csrf.php");
	require_once("../php/team.php");
	require_once("commentform.php");
	require_once("../php/userTeam.php");
	require_once("../php/form/commentfunctions.php");
	$mysqli = $mysqli = MysqliConfiguration::getMysqli();
	$team = Team::getTeamByTeamId($mysqli, $_GET['teamId']);
	$pageId = $team->getTeamId();
	$pageType = 1;
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
	<title><?php echo $team->getTeamName()?></title>
</head>
<body>
	<?php /* navBar()*/ ?>
	<div class="container">
		<div id="outputArea"></div>
		<div class="row">
			<aside class="col-lg-4 information">
				<?php
				echo "<h3>" . $team->getTeamName() . "</h3>";
				echo "<h5>" . $team->getTeamCause() . "</h5>";

				echo "
						<form action=\"../php/form/permissionsprocessor.php\" method='post'>
						";
				echo generateInputTags();
				echo "	<input type='hidden' name=\"permissionType\" value=\"1\">
								<input type='hidden' name=\"teamId\" value=\"" . $team->getTeamId() . "\">
								<button type='submit' class='btn btn-primary'>Edit Permissions</button>
							</form>
							";
				?>
			</aside>
			<div id="mainContent" class="col-lg-8">
				<section>
						<form id="commentForm" class="commentBox" action="../php/form/commentprocessor.php" method="post">
						<?php
							echo generateInputTags();
						commentForm($pageType, $pageId);
						?>
				</section>
				<section class="comments">
					<?php displayTeamComment($pageId)?>
				</section>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</body>
</html>