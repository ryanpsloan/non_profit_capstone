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

try{
	$mysqli = MysqliConfiguration::getMysqli();

	if($_POST["permissionEdit"] === 1){
		if($_SESSION["permissionType"] === 1)
		{
			$userArray = UserTeam::getUserTeamByTeamId($mysqli, $_SESSION["teamId"]);
			$profileIds = array();
			$profileNames = array();
			$output = array();

			for($i= 0; $i<count($userArray); $i++){
				$profileIds[] = $userArray[$i]->getProfileId();
				$profileNames [] = $userArray[$i]->getFirstName() . " " . $userArray[$i]->getLastName();
 			}

		/*	for($j=0; $j<=count($profileIds); $j++) {
				$profiles = Profile::getProfileByProfileId($mysqli, $profileIds[$j]);
			}*/

			/*for($i2 = 0; $i2<=count($profiles); $i2++){
				$profileNames[] = $profiles[$i2]->getFirstName() . " " . $profiles[$i2]->
					getLastName();
			}*/

			$html1 = "<p><form id='userTeamPermissionForm' method='post'>$profileNames[$j2] . ' ' .
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

			$html3 =	"<select id='commentPermission'>
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

			for($j2 = 0; $j2<=count($userArray); $j2++){
				//Makes the current role in team status as the displayed value on the drop down
				$replaceRoleInTeamSelected = str_replace("value=\"$userArray[$j2]->getRoleInTeam()\"",
																	  "value=\"$userArray[$j2]->getRoleinTeam()\" selected", $html1);
				//Makes the current team permission the selected value on the drop down
				$replaceTeamPermissionSelected = str_replace ("value=\"$userArray[$j2]->getTeamPermission()\"",
																  			"value=\"$userArray[$j2]->getTeamPermission()\" selected", $html2);
				//Makes the current comment permission the selected value on the drop down
				$replaceCommentSelected = str_replace("value=\"$userArray[$j2]->getCommentPermission()\"",
																  "value=\"$userArray[$j2]->getCommentPermission()\" selected", $html3);
				//Makes the current invite permission the selected value on the drop down
				$replaceInvitePermissionSelected = str_replace("value=\"$userArray[$j2]->getInvitePermission\"",
																			  "value=\"$userArray[$j2]->getInvitePermission\" selected", $html4);
				//Makes the current ban status the selected value on the drop down
				$replaceBanStatusSelected = str_replace("value=\"$userArray[$j2]->getBanStatus()\"",
																	 "value=\"$userArray[$j2]->getBanStatus()\" selected", $html5);
				//What will be displayed to the user in the HTML
				$output[] = $html1 . " " . $html2 . " " . $html3 . " " . $html4 . " " . $html5;
			}
		} elseif($_SESSION["permissionType"] === 2){
			$teamArray = TeamEvent::getTeamEventByEventId($mysqli, $_SESSION["eventId"]);
			$teamIds= array();
			$teamNames = array();
			$output = array();

			for($i = 0; $i<=count($teamArray); $i++){
				$teamIds[] = $teamArray[$i]->teamId;
				$teamNames [] = $teamArray[$i]->getTeamName();
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
							$teamNames[$j2] . \" \" .
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
			for($j2 = 0; $j2<=count($teamNames); $j2++){
				//Makes the current team role the selected value on the drop down
				$replaceTeamRoleSelected = str_replace("value=\"$teamArray[$j2]->getTeamRole()\"",
																	"value=\"$teamArray[$j2]->getTeamRole()\" selected", $html1);
				//Makes the current comment permission the selected value on the drop down
				$replaceCommentSelected = str_replace("value=\"$teamArray[$j2]->getCommentPermission()\"",
																  "value=\"$teamArray[$j2]->getCommentPermission()\" selected", $html2);
				//Makes the current ban status the selected value on the drop down
				$replaceBanStatusSelected = str_replace("value=\"$teamArray[$j2]->getBanStatus()\"",
																	 "value=\"$teamArray[$j2]->getBanStatus()\" selected", $html3);
				//What will be displayed to the user in the HTML
				$output = $html1 . " " . $html2 . " " . $html3;
			}
		} elseif($_SESSION["permissionType"] === 3){
			$userArray = UserEvent::getUserEventByEventId($mysqli, $_SESSION["eventId"]);
			$profileIds = array();
			$profileNames = array();
			$output = array();

			for($i = 0; $i<=count($userArray); $i++){
				$profileIds[] = $userArray->profileId;
				$profileNames[] = $userArray->getFirstName() . " " . $userArray->getLastName();
			}


			// Deprecated due to calling mysql too many times

			/*for($j = 0; $j<=count($profileIds); $j++){
				$profiles = Profile::getProfileByProfileId($mysqli, $profileIds[$j]);
			}
			for($i2 = 0; $i2<=count($profiles); $i2++){
				$profileNames[] = $profiles[$i2]->getFirstName() . " " . $profiles[$i2]->
					getLastName();
			}*/


			$html1 = "<p><form id='userEventPermissionForm' method='post'>$profileNames[$j2] . ' ' .
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
				$replaceTeamPermissionSelected = str_replace("value=\"$userArray[$j2]->userEventRole\"",
																			"value=\"$userArray[$j2]->userEventRole\" selected", $html1);
				//Makes the current comment permission the selected value on the drop down
				$replaceCommentSelected = str_replace("value=\"$userArray[$j2]->commentPermission\"",
																	"value=\"$userArray[$j2]->commentPermission\" selected", $html2);
				//Makes the current ban status the selected value on the drop down
				$replaceBanStatusSelected = str_replace("value=\"$userArray[$j2]->banStatus\"",
																	"value=\"$userArray[$j2]->banStatus\" selected", $html3);
				//What will be displayed to the user in the HTML
				$output[] = $html1 . " " . $html2 . " " . $html3;
			}
		} else {
			throw(new UnexpectedValueException("Not a valid permission parameter."));
		}
	}
} catch (Exception $exception){
	echo "There has been an error" . " " . $exception->getMessage();
}
