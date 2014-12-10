<?php
/**
 * Creates functions for permissions for easier management and maintenance of the permission processor.
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */


function userTeamPermissions()
{
	// TODO add names to forms
	// TODO array is now dual index must account for this\
	// TODO array is indexed by index 1 ([x][]) is the userTeam with profile and index 2 ([][x]) is userTeam or
	// TODO profile

	//NOTICE: Hello this is a notice
	$userArray = UserTeam::getUserTeamByTeamId($mysqli, $_POST["teamId"]);
	$profileIds = array();
	$profileNames = array();
	$output = array();

	for($i = 0; $i < count($userArray); $i++) {
		$profileIds[] = $userArray[$i][0]->getProfileId();
		$profileNames [] = $userArray[$i][1]->getFirstName() . " " . $userArray[$i][1]->getLastName();
	}

	/*	for($j=0; $j<=count($profileIds); $j++) {
			$profiles = Profile::getProfileByProfileId($mysqli, $profileIds[$j]);
		}*/

	/*for($i2 = 0; $i2<=count($profiles); $i2++){
		$profileNames[] = $profiles[$i2]->getFirstName() . " " . $profiles[$i2]->
			getLastName();
	}*/

	$html1 = "<p><form id='userTeamPermissionForm' method='post'>
							<select id='RoleInTeam'>
							<option value='1'>Founder</option>
							<option value='2'>Event Organizer</option>
							<option value='3'>Normal Member</option>
							</select>
							 ";

	$html2 = "<select id='TeamPermission'>
							 <option value='1'>Can Edit</option>
							 <option value='2'>Cannot Edit</option>
							 </select>
							";

	$html3 = "<select id='commentPermission'>
							<option value='1'>Can Comment</option>
							<option value='2'>Cannot Comment</option>
							</select>
							";

	$html4 = "<select id='invitePermission'>
							<option id='1'>Can Invite</option>
							<option id='2'>Cannot Invite</option>
							</select>
							";

	$html5 = "<select>
								<option value='1'>Not Banned</option>
								<option value='2'>Banned</option>
							</select>
							<input type='submit' value='Submit'></form></p> <br/>";

	for($j2 = 0; $j2 <= count($userArray); $j2++) {
		//Makes the current role in team status as the displayed value on the drop down
		$replaceRoleInTeamSelected = str_replace("value=\"$userArray[$j2][0]->getRoleInTeam()\"",
			"value=\"$userArray[$j2][0]->getRoleinTeam()\" selected", $html1);
		//Makes the current team permission the selected value on the drop down
		$replaceTeamPermissionSelected = str_replace("value=\"$userArray[$j2][0]->getTeamPermission()\"",
			"value=\"$userArray[$j2][0]->getTeamPermission()\" selected", $html2);
		//Makes the current comment permission the selected value on the drop down
		$replaceCommentSelected = str_replace("value=\"$userArray[$j2][0]->getCommentPermission()\"",
			"value=\"$userArray[$j2][0]->getCommentPermission()\" selected", $html3);
		//Makes the current invite permission the selected value on the drop down
		$replaceInvitePermissionSelected = str_replace("value=\"$userArray[$j2][0]->getInvitePermission\"",
			"value=\"$userArray[$j2][0]->getInvitePermission\" selected", $html4);
		//Makes the current ban status the selected value on the drop down
		$replaceBanStatusSelected = str_replace("value=\"$userArray[$j2][0]->getBanStatus()\"",
			"value=\"$userArray[$j2][0]->getBanStatus()\" selected", $html5);
		//What will be displayed to the user in the HTML
		$output[] = $profileNames[$j2] . " " . $replaceRoleInTeamSelected . " " . $replaceTeamPermissionSelected . " " .
						$replaceCommentSelected . " " . $replaceInvitePermissionSelected . " " .
						$replaceBanStatusSelected;
	}

}

function teamEventPermissions(){
	// Array to check permission types for teamEvent
	//TODO: Array is now dual index must account for this.
	//TODO: Fix html1 incrementer for index on teamName
	$teamArray = TeamEvent::getTeamEventByEventId($mysqli, $_SESSION["eventId"]);
	$teamIds= array();
	$teamNames = array();
	$output = array();

	for($i = 0; $i<=count($teamArray); $i++){
		$teamIds[] = $teamArray[$i][0]->teamId;
		$teamNames [] = $teamArray[$i][1]->getTeamName();
	}

	//Deprecated due to over calling to mysql
	/*for($j = 0; $j<=count($teamIds); $j++){
		$teams = Team::getTeamByTeamId($mysqli, $teamIds[$j]);
	}
	for($i2 = 0; $i2<=count($teams); $i2++){
		$teamNames[] = $teams[$i2]->getTeamName();
	}*/

	//The drop down for the role in event option and the start of the form
	$html1 = "<form value='teamEventPermissions' action='permissionsupdate.php' method='post'>
							Role In Event:
							<select>
							<option value='1'>Event Founders</option>
							<option value='2'>Event Assistants</option>
							<option value='3'>Event Attendants</option>
							</select>";
	//The drop down for the comment permission option
	$html2 =	"<select id='commentPermission'>
							<option value='1'>Can Comment</option>
							<option value='2'>Cannot Comment</option>
							</select>
							";
	//The drop down for the ban option
	$html3 = "<select>
								<option value='1'>Not Banned</option>
								<option value='2'>Banned</option>
							</select>
							<input type='submit' value='Submit'></form></p><br/>";


	for($j2 = 0; $j2<=count($teamNames); $j2++) {
		//Makes the current team role the selected value on the drop down
		$replaceTeamRoleSelected = str_replace("value=\"$teamArray[$j2][0]->getTeamRole()\"",
			"value=\"$teamArray[$j2][0]->getTeamRole()\" selected", $html1);
		//Makes the current comment permission the selected value on the drop down
		$replaceCommentSelected = str_replace("value=\"$teamArray[$j2][0]->getCommentPermission()\"",
			"value=\"$teamArray[$j2][0]->getCommentPermission()\" selected", $html2);
		//Makes the current ban status the selected value on the drop down
		$replaceBanStatusSelected = str_replace("value=\"$teamArray[$j2][0]->getBanStatus()\"",
			"value=\"$teamArray[$j2][0]->getBanStatus()\" selected", $html3);
		//What will be displayed to the user in the HTML
		$output[] = $teamNames[$j2] . " " . $replaceTeamRoleSelected . " " . $replaceCommentSelected . " " .
		$replaceBanStatusSelected;}
}

function userEventPermission(){
	//Checks for permissions for userEvent

	// TODO: Array is now dual index must account for this
	$userArray = UserEvent::getUserEventByEventId($mysqli, $_SESSION["eventId"]);
	$profileIds = array();
	$profileNames = array();
	$output = array();

	for($i = 0; $i<=count($userArray); $i++){
		$profileIds[] = $userArray[$i][0]->profileId;
		$profileNames[] = $userArray[$i][0]->getFirstName() . " " . $userArray[$i][0]->getLastName();
	}


	// Deprecated due to calling mysql too many times

	/*for($j = 0; $j<=count($profileIds); $j++){
		$profiles = Profile::getProfileByProfileId($mysqli, $profileIds[$j]);
	}
	for($i2 = 0; $i2<=count($profiles); $i2++){
		$profileNames[] = $profiles[$i2]->getFirstName() . " " . $profiles[$i2]->
			getLastName();
	}*/


	$html1 = "<p><form id='userEventPermissionForm' method='post'>
							<select id='userEventRole'>
							 <option value='1'>Event Organizer</option>
							 <option value='2'>Normal Member</option>
							 </select>
							 ";

	$html2 = "<select id='commentPermission'>
							<option value='1'>Can Comment</option>
							<option value='2'>Cannot Comment</option>
							</select>
							";

	$html3 = "<select id='banStatus'>
								<option value='1'>Not Banned</option>
								<option value='2'>Banned</option>
							</select>
							<input type='submit' value='Submit'></form></p> <br/>";


	for($j2 = 0; $j2<=count($profileNames); $j2++) {
		//Makes the current team permission the selected value on the drop down
		$replaceTeamPermissionSelected = str_replace("value=\"$userArray[$j2][0]->userEventRole\"",
			"value=\"$userArray[$j2][0]->userEventRole\" selected", $html1);
		//Makes the current comment permission the selected value on the drop down
		$replaceCommentSelected = str_replace("value=\"$userArray[$j2][0]->commentPermission\"",
			"value=\"$userArray[$j2][0]->commentPermission\" selected", $html2);
		//Makes the current ban status the selected value on the drop down
		$replaceBanStatusSelected = str_replace("value=\"$userArray[$j2][0]->banStatus\"",
			"value=\"$userArray[$j2][0]->banStatus\" selected", $html3);
		//What will be displayed to the user in the HTML
		$output[] = $profileNames[$j2] . " " . $replaceTeamPermissionSelected . " " . $replaceCommentSelected . " " .
						$replaceBanStatusSelected;
	}
}