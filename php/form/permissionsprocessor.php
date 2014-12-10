<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../event.php");
require_once("../team.php");
require_once("../comment.php");
require_once("../user.php");
require_once("../profile.php");
require_once("../teamevent.php");
require_once("../userevent.php");
require_once("../userTeam.php");
require_once("permissionfunction.php");
/* TODO: arrays calling profile functions are dual index, need to add functionality to make sure the indexes are
   TODO: correct. Index of [0][x] Will call UserTeam info, index of [0][x+1] Will call profileInfo.
	TODO: Potential fix is to increment by 2 after initial 0. Modulo by 2?*/

// TODO: Fix html1 profileName no longer within loop

try{
	$mysqli = MysqliConfiguration::getMysqli();

		if($_POST["permissionType"] === 1) {
			//Function to call in the permission changing code
			userTeamPermissions();
		} elseif($_POST["permissionType"] === 2){
			//Function to call in the permission changing code
			teamEventPermissions();

		} elseif($_POST["permissionType"] === 3){
			//Function to call in the permission changing code
			userEventPermission();

		} else {
			throw(new UnexpectedValueException("Not a valid permission parameter."));
		}

} catch (Exception $exception){
	echo "There has been an error" . " " . $exception->getMessage();
}
