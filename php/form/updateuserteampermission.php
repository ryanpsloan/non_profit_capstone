<?php
	session_start();
	require_once("/etc/apache2/capstone-mysql/helpabq.php");
	require_once("csrf.php");
	require_once("../userTeam.php");

	try{
		$mysqli = MysqliConfiguration::getMysqli();
		if(($mysqli = MysqliConfiguration::getMysqli()) === false){
			throw(new mysqli_sql_exception("Server connection failed, please try again later."));
		}

		if(@isset($_POST['teamId']) === false || @isset($_POST['roleInTeam'])
				=== false ||@isset ($_POST['profileId']) === false || @isset($_POST['teamPermission']) == false || @isset($_POST['commentPermission']) === false
				|| @isset($_POST['invitePermission']) === false || @isset($_POST['banStatus']) === false) {
			throw(new UnexpectedValueException("The permission parameters are invalid please try again."));
		}

		$teamId = $_POST['teamId'];
		$teamId = intval($teamId);

		$profileId = $_POST['profileId'];
		$profileId = intval($profileId);

		$roleInTeam = $_POST['roleInTeam'];
		$roleInTeam = intval($roleInTeam);

		$teamPermission = $_POST['teamPermission'];
		$teamPermission = intval($teamPermission);

		$commentPermission = $_POST['commentPermission'];
		$commentPermission = intval($commentPermission);

		$invitePermission = $_POST['invitePermission'];
		$invitePermission = intval($invitePermission);

		$banStatus = $_POST['banStatus'];
		$banStatus = intval($banStatus);

		if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
			throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
		}

		/*$permissionsUpdate = new UserTeam ($_POST['profileId'], $_POST['teamId'], $_POST['roleInTeam'],
														$_POST['teamPermission'], $_POST['commentPermission'],
														$_POST['invitePermission'], $_POST['banStatus']);*/
		$permissionsUpdate = UserTeam::getUserTeamByProfileTeamId($mysqli, $profileId, $teamId);
		$permissionsUpdate[0][0]->setRoleInTeam($roleInTeam);
		$permissionsUpdate[0][0]->setTeamPermission($teamPermission);
		$permissionsUpdate[0][0]->setCommentPermission($commentPermission);
		$permissionsUpdate[0][0]->setInvitePermission($invitePermission);
		$permissionsUpdate[0][0]->setBanStatus($banStatus);

		$permissionsUpdate[0][0]->update($mysqli);

	}catch (Exception $exception){
		echo "We have encountered an error." . " " . $exception->getMessage();
	}