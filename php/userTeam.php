<?php
/**
 * mySQL Enabled Intersection Table for userTeam
 *
 * This is a mySQL enabled container for User Team interaction at a nonprofit site.
 *
 *
 * User: Martin
 */

//Required for initializing profile class
require_once("profile.php");

//setup User Class and respective fields
class UserTeam {

	//profileId for the UserTeam intersection table; this is the primary key and foreign key.
	private $profileId;

	//teamId for the UserTeam intersection table; this is the primary key and foreign key.
	private $teamId;

	//role in team for UserTeam intersection table
	private $roleInTeam;

	//team permission for the UserTeam intersection table
	private $teamPermission;

	//permission to add comments on the UserTeam intersection table
	private $commentPermission;

	//permission to invite other members on the UserTeam intersection table
	private $invitePermission;

	//whether or not they are on a team for the UserTeam intersection table
	private $banStatus;

	/** constructor for the UserTeam
	 *
	 * @param int $newProfileId for profileId
	 * @param int $newTeamId  for teamId
	 * @param int $newRoleInTeam for roleInTeam
	 * @param int $newTeamPermission for teamPermission
	 * @param int $newCommentPermission for commentPermission
	 * @param int $newInvitePermission for invitePermission
	 * @param int $newBanStatus for banStatus
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/

	public function __construct($newProfileId, $newTeamId, $newRoleInTeam, $newTeamPermission, $newCommentPermission,
										 $newInvitePermission, $newBanStatus) {
		try {
			$this->setProfileId($newProfileId);
			$this->setTeamId($newTeamId);
			$this->setRoleInTeam($newRoleInTeam);
			$this->setTeamPermission($newTeamPermission);
			$this->setCommentPermission($newCommentPermission);
			$this->setInvitePermission($newInvitePermission);
			$this->setBanStatus($newBanStatus);

			// catch exceptions and rethrow to caller
		} catch(UnexpectedValueException $unexpectedValue) {
			throw(new UnexpectedValueException ("Unable to construct User", 0, $unexpectedValue));

		} catch(RangeException $range) {
			$range->getMessage();
			throw(new RangeException("Unable to construct User", 0, $range));
		}
	}

	/**
	 * gets the value of Profile Id
	 *
	 * @return int profileId
	 **/
	public function getProfileId() {
		return($this->profileId);
	}

	/**
	 * sets the value of profile id
	 *
	 * @param string $newProfileId profile id
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if profile id isn't positive
	 **/

	public function setProfileId($newProfileId) {

		//first, ensure the profile id is an integer
		if(filter_var($newProfileId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Profile id $newProfileId is not numeric"));
		}

		// second, convert the profile id to an integer and enforce it's positive
		$newProfileId = intval($newProfileId);
		if($newProfileId <= 0) {
			throw(new RangeException ("Profile Id $newProfileId is not positive"));
		}
		//remove profile Id from quarantine
		$this->profileId = $newProfileId;
	}

	/**
	 * gets the value of team Id
	 *
	 * @return int teamId
	 **/
	public function getTeamId() {
		return($this->teamId);
	}

	/**
	 * sets the value of team id
	 *
	 * @param string $newTeamId team id
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if team id isn't positive
	 **/
	public function setTeamId($newTeamId) {

		//first, ensure the team id is an integer
		if(filter_var($newTeamId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Team id $newTeamId is not numeric"));
		}

		// second, convert the team id to an integer and enforce it's positive
		$newTeamId = intval($newTeamId);
		if($newTeamId <= 0) {
			throw(new RangeException ("Team Id $newTeamId is not positive"));
		}
		//remove teamId from quarantine
		$this->teamId = $newTeamId;
	}

	/**
	 * gets the value of roleInTeam
	 *
	 * @return int roleInTeam
	 **/
	public function getRoleInTeam() {
		return($this->roleInTeam);
	}

	/**
	 * sets the value of role in team
	 *
	 * @param string $newRoleInTeam  roleInTeam
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if roleInTeam isn't positive
	 **/

	public function setRoleInTeam($newRoleInTeam) {

		//first, ensure the roleInTeam is an integer
		if(filter_var($newRoleInTeam, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Role in team $newRoleInTeam is not numeric"));
		}

		// second, convert the roleInTeam to an integer and enforce it's positive
		$newRoleInTeam = intval($newRoleInTeam);
		if($newRoleInTeam <= 0) {
			throw(new RangeException ("Role in team $newRoleInTeam is not positive"));
		}
		//remove roleInTeam from quarantine
		$this->roleInTeam = $newRoleInTeam;
	}

	/**
	 * gets the value of teamPermission
	 *
	 * @return int teamPermission
	 **/
	public function getTeamPermission() {
		return($this->teamPermission);
	}

	/**
	 * sets the value of team permission
	 *
	 * @param string $newTeamPermission  teamPermission
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if teamPermission isn't positive
	 **/
	public function setTeamPermission($newTeamPermission) {

		//first, ensure the teamPermission is an integer
		if(filter_var($newTeamPermission, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Team permission $newTeamPermission is not numeric"));
		}

		// second, convert the teamPermission to an integer and enforce it's positive
		$newTeamPermission = intval($newTeamPermission);
		if($newTeamPermission <= 0) {
			throw(new RangeException ("Team permissions $newTeamPermission is not positive"));
		}
		//remove teamPermission from quarantine
		$this->teamPermission = $newTeamPermission;
	}

	/**
	 * gets the value of commentPermission
	 *
	 * @return int commentPermission
	 **/
	public function getCommentPermission() {
		return($this->commentPermission);
	}

	/**
	 * sets the value of comment permission
	 *
	 * @param string $newCommentPermission  commentPermission
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if commentPermission isn't positive
	 **/
	public function setCommentPermission($newCommentPermission) {

		//first, ensure the commentPermission is an integer
		if(filter_var($newCommentPermission, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Comment permission $newCommentPermission is not numeric"));
		}

		// second, convert the commentPermission to an integer and enforce it's positive
		$newCommentPermission = intval($newCommentPermission);
		if($newCommentPermission <= 0) {
			throw(new RangeException ("Comment permissions $newCommentPermission is not positive"));
		}
		//remove teamPermission from quarantine
		$this->commentPermission = $newCommentPermission;
	}

	/**
	 * gets the value of invitePermission
	 *
	 * @return int invitePermission
	 **/
	public function getInvitePermission() {
		return($this->invitePermission);
	}

	/**
	 * sets the value of invite permission
	 *
	 * @param string $newInvitePermission  invitePermission
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if invitePermission isn't positive
	 **/
	public function setInvitePermission($newInvitePermission) {

		//first, ensure the invitePermission is an integer
		if(filter_var($newInvitePermission, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Invite permission $newInvitePermission is not numeric"));
		}

		// second, convert the commentPermission to an integer and enforce it's positive
		$newInvitePermission = intval($newInvitePermission);
		if($newInvitePermission <= 0) {
			throw(new RangeException ("Invite permission $newInvitePermission is not positive"));
		}
		//remove invitePermission from quarantine
		$this->invitePermission = $newInvitePermission;
	}

	/**
	 * gets the value of banStatus
	 *
	 * @return int banStatus
	 **/
	public function getBanStatus() {
		return($this->banStatus);
	}

	/**
	 * sets the value of banStatus
	 *
	 * @param string $newBanStatus  banStatus
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if banStatus isn't positive
	 **/
	public function setBanStatus($newBanStatus) {

		//first, ensure the banStatus is an integer
		if(filter_var($newBanStatus, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Ban status $newBanStatus is not numeric"));
		}

		// second, convert the banStatus to an integer and enforce it's positive
		$newBanStatus = intval($newBanStatus);
		if($newBanStatus <= 0) {
			throw(new RangeException ("Ban Status $newBanStatus is not positive"));
		}
		//remove banStatus from quarantine
		$this->banStatus = $newBanStatus;
	}

	/**
	 * inserts this profile and team to mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the profileId is null (i.e., don't insert a user that already exists)
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("not a new user"));
		}

		// enforce the teamId is null (i.e., don't insert a user that already exists)
		if($this->teamId === null) {
			throw(new mysqli_sql_exception("not a new team"));
		}

		// create query template
		$query     = "INSERT INTO userTeam(profileId, teamId, roleInTeam, teamPermission, commentPermission, invitePermission,
														banStatus) VALUES(?, ?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("iiiiiii", $this->profileId, $this->teamId, $this->roleInTeam, $this->teamPermission,
			$this->commentPermission, $this->invitePermission, $this->banStatus);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

	}
	/**
	 * deletes this User and Team from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the profileId and teamId is not null (i.e., don't delete a user that hasn't been inserted)
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("Unable to delete a user that does not exist"));
		}

		if($this->teamId === null) {
			throw(new mysqli_sql_exception("Unable to delete a team that does not exist"));
		}
		// create query template
		$query     = "DELETE FROM userTeam WHERE profileId = ? AND teamId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("ii", $this->profileId, $this->teamId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates this User and Team in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the profileId and teamId is not null (i.e., don't update a user that hasn't been inserted)
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("Unable to update a user that does not exist"));
		}

		if($this->teamId === null) {
			throw(new mysqli_sql_exception("Unable to update a team that does not exist"));
		}

		// create query template
		$query     = "UPDATE userTeam SET roleInTeam = ?,teamPermission = ?, commentPermission = ?, invitePermission = ?, banStatus = ?
 							WHERE profileId = ? AND teamId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("iiiiiii", $this->roleInTeam, $this->teamPermission, $this->commentPermission,
																	 $this->invitePermission, $this->banStatus, $this->profileId, $this->teamId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * gets the UserTeam by profile Id
	 *
	 * @param $mysqli mysqli object
	 * @param mixed $profileId
	 * @return array|null
	 * @throw mysqli_sql_exception if unable to properly execute statement
	 */

	public static function getUserTeamByProfileId($mysqli,$profileId){

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the profileId before searching
		$profileId = trim($profileId);
		$profileId = filter_var($profileId, FILTER_VALIDATE_INT);

		// create query template
		$query = "SELECT profileId, teamId, roleInTeam, teamPermission, commentPermission, invitePermission, banStatus
					 FROM userTeam WHERE profileId = ? ORDER BY profileId, teamId";

		//prepare statement
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		//bind the profileId to the place holder in the template
		$wasClean = $statement->bind_param("i", $profileId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
		//get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set."));
		}

		//turn the results into an array
		$profileIdSearch = array();

		// loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$profile = new userTeam($row["profileId"], $row["teamId"], $row["roleInTeam"], $row["teamPermission"],
												$row["commentPermission"], $row["invitePermission"], $row["banStatus"]);
				$profileIdSearch [] = $profile;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to userteam", 0, $exception));
			}
		}

		// return
		if($result->num_rows === 0) {
			return(null);
		} else {
			return($profileIdSearch);
		}
	}

	/**
	 * gets the UserTeam by team Id
	 *
	 * @param $mysqli mysqli object
	 * @param mixed $teamId
	 * @return array|null
	 * @throw mysqli_sql_exception if unable to execute method
	 */

	public static function getUserTeamByTeamId($mysqli,$teamId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the team id before searching
		$teamId = trim($teamId);
		$teamId = filter_var($teamId, FILTER_VALIDATE_INT);

		// create query template
		$query = "
		SELECT userTeam.profileId, userTeam.teamId, profile.profileId, profile.userId, profile.userTitle, profile.firstName, profile.midInit,
		profile.lastName, profile.bio, profile.attention, profile.street1, profile.street2, profile.city, profile.state,
		profile.zipCode, userTeam.roleInTeam, userTeam.teamPermission, userTeam.commentPermission, userTeam.invitePermission,
		userTeam.banStatus
		FROM userTeam
		INNER JOIN profile ON userTeam.profileId = profile.profileId
		WHERE teamId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the teamId to the place holder in the template
		$wasClean = $statement->bind_param("i", $teamId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
		//get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set."));
		}

		//turn the results into an array.
		$teamMembershipMap = array();

		// NOTICE: When calling the $teamMembershipMap array index of [0][x] will call the userTeam info
		// NOTICE: When calling the index of [0][x+1] you will gain the profile information.

		// Loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {
			try {
				$team = new userTeam($row["profileId"], $row["teamId"], $row["roleInTeam"], $row["teamPermission"],
					$row["commentPermission"], $row["invitePermission"], $row["banStatus"]);
				$profile = new Profile($row["profileId"], $row["userId"],  $row["userTitle"], $row["firstName"], $row["midInit"], $row["lastName"],
											  $row["bio"], $row["attention"], $row["street1"], $row["street2"], $row["city"],
											  $row["state"], $row["zipCode"]);
				$teamMembershipMap[] = array($team, $profile);
			} catch(Exception $exception) {
				throw(new mysqli_sql_exception("Unable to convert row to userTeam", 0, $exception));
			}
		}
		// return
		if($result->num_rows === 0) {
			return(null);
		} else {
			return($teamMembershipMap);
		}
	}

	/**
	 * gets the UserTeam by profileId and teamId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param mixed $profileId to search for
	 * @param mixed $teamId to search for
	 * @return int profile and team found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/



	public static function getUserTeamByProfileTeamId(&$mysqli, $profileId, $teamId) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the profileId and teamId before searching
		$profileId = filter_var($profileId, FILTER_VALIDATE_INT);
		if($profileId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		$teamId = filter_var($teamId, FILTER_VALIDATE_INT);
		if($teamId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT userTeam.profileId, userTeam.teamId, profile.profileId, profile.userId, profile.userTitle,
					profile.firstName, profile.midInit, profile.lastName, profile.bio, profile.attention, profile.street1,
					profile.street2, profile.city, profile.state, profile.zipCode, userTeam.roleInTeam,
					userTeam.teamPermission, userTeam.commentPermission, userTeam.invitePermission,
					userTeam.banStatus
					FROM userTeam
					INNER JOIN profile ON userTeam.profileId = profile.profileId
					WHERE userTeam.profileId = ? AND userTeam.teamId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the profileId and teamId to the place holder in the template
		$wasClean = $statement->bind_param("ii", $profileId, $teamId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}
		//primary key can only one of two things null or integer
		//if there's a result, we can show it
		//if not error code 404
		$row = $result->fetch_assoc();
		$userTeamProfile = array();
		//covert the associative array to a userId

		//NOTICE: When calling the $userTeamProfile Array the index $userTeamProfile[x][0] Will return the UserTeam info
		//NOTICE: Calling the index $userTeamProfile[x][1] Will call the profile info.
		if($row !== null) {
			try {
				$userTeam = new userTeam($row["profileId"], $row["teamId"], $row["roleInTeam"], $row["teamPermission"],
												 $row["commentPermission"], $row["invitePermission"], $row["banStatus"]);
				$profile = new Profile($row["profileId"], $row["userId"],  $row["userTitle"], $row["firstName"], $row["midInit"], $row["lastName"],
					$row["bio"], $row["attention"], $row["street1"], $row["street2"], $row["city"],
					$row["state"], $row["zipCode"]);
				$userTeamProfile[] = array ($userTeam, $profile);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));

			}
			//if we get a profileId and teamId I'm lucky and show it
			return ($userTeamProfile);
		} else {
			//404 User not found
			return (null);
		}
	}

	/**
	 * gets the UserTeam by roleInTeam
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $roleInTeam  roleInTeam to search for
	 * @return int UserTeam found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserTeamByRoleInTeam(&$mysqli, $roleInTeam) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the roleInTeam before searching
		$roleInTeam = trim($roleInTeam);
		$roleInTeam = filter_var($roleInTeam, FILTER_VALIDATE_INT);
		$roleInTeam = intval($roleInTeam);
		if($roleInTeam === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT profileId, teamId , roleInTeam, teamPermission, commentPermission, invitePermission, banStatus
					 FROM userTeam WHERE roleInTeam = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the roleInTeam to the place holder in the template
		$wasClean = $statement->bind_param("i", $roleInTeam);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		//many users can have different permissions.
		//if there's a result, we can show it
		//if not error code 404

		//userArrayCounter = 0
		$roleInTeamArray = array();
		while(($row = $result->fetch_assoc()) !== null) {

			//covert the associative array to a userId and repeat for all permissions
			try {
				$userTeam = new userTeam($row["profileId"], $row["teamId"], $row["roleInTeam"], $row["teamPermission"],
					$row["commentPermission"], $row["invitePermission"], $row["banStatus"]);
				//build empty array for sql to fill
				$roleInTeamArray [] = $userTeam;

			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));
			}
		}
		//if we get a roleInTeam I'm lucky and show it
		if ($result->num_rows ===0) {
			return (null);
		} else {

			return ($roleInTeamArray);
		}
	}

	/**
	 * gets the UserTeam by commentPermission
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $commentPermission  commentPermissions to search for
	 * @return int UserTeam found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserTeamByCommentPermission(&$mysqli, $commentPermission) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the roleInTeam before searching
		$commentPermission = trim($commentPermission);
		$commentPermission = filter_var($commentPermission, FILTER_VALIDATE_INT);
		$commentPermission = intval($commentPermission);
		if($commentPermission === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT profileId, teamId , roleInTeam, teamPermission, commentPermission, invitePermission, banStatus
					 FROM userTeam WHERE commentPermission = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the roleInTeam to the place holder in the template
		$wasClean = $statement->bind_param("i", $commentPermission);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		//many users can have different permissions.
		//if there's a result, we can show it
		//if not error code 404

		//userArrayCounter = 0
		$commentPermissionArray = array();
		while(($row = $result->fetch_assoc()) !== null) {

			//covert the associative array to a profileId and teamId and repeat for all commentPermissions
			try {
				$userTeam = new userTeam($row["profileId"], $row["teamId"], $row["roleInTeam"], $row["teamPermission"],
					$row["commentPermission"], $row["invitePermission"], $row["banStatus"]);
				//build empty array for sql to fill
				$commentPermissionArray [] = $userTeam;

			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));
			}
		}
		//if we get a commentPermission I'm lucky and show it
		if ($result->num_rows ===0) {
			return (null);
		} else {

			return ($commentPermissionArray);
		}
	}

	/**
	 * gets the UserTeam by invitePermission
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $invitePermission  invitePermission to search for
	 * @return int UserId TeamId found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserTeamByInvitePermission(&$mysqli, $invitePermission) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the invitePermission before searching
		$invitePermission = trim($invitePermission);
		$invitePermission = filter_var($invitePermission, FILTER_VALIDATE_INT);
		$invitePermission = intval($invitePermission);
		if($invitePermission === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT profileId, teamId , roleInTeam, teamPermission, commentPermission, invitePermission, banStatus
					 FROM userTeam WHERE invitePermission = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the invitePermission to the place holder in the template
		$wasClean = $statement->bind_param("i", $invitePermission);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		//many users can have different permissions.
		//if there's a result, we can show it
		//if not error code 404

		//userArrayCounter = 0
		$invitePermissionArray = array();
		while(($row = $result->fetch_assoc()) !== null) {

			//covert the associative array to a profileId and teamId and repeat for all invitePermissions
			try {
				$userTeam = new userTeam($row["profileId"], $row["teamId"], $row["roleInTeam"], $row["teamPermission"],
					$row["commentPermission"], $row["invitePermission"], $row["banStatus"]);
				//build empty array for sql to fill
				$invitePermissionArray [] = $userTeam;

			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));
			}
		}
		//if we get a invitePermission I'm lucky and show it
		if ($result->num_rows ===0) {
			return (null);
		} else {

			return ($invitePermissionArray);
		}
	}

	/**
	 * gets the UserTeam by banStatus
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $banStatus banStatus to search for
	 * @return int UserId and TeamId found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserTeamByBanStatus(&$mysqli, $banStatus) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the banStatus before searching
		$banStatus = trim($banStatus);
		$banStatus = filter_var($banStatus, FILTER_VALIDATE_INT);
		$banStatus = intval($banStatus);
		if($banStatus === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT profileId, teamId , roleInTeam, teamPermission, commentPermission, invitePermission, banStatus
					 FROM userTeam WHERE banStatus = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the banStatus to the place holder in the template
		$wasClean = $statement->bind_param("i", $banStatus);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("unable to bind parameters"));
		}
		//execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("unable to execute mySQL statement"));
		}
		// get result from the SELECT query
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("unable to get result set"));
		}

		//many users can have different banStatus.
		//if there's a result, we can show it
		//if not error code 404

		//userArrayCounter = 0
		$banStatusArray = array();
		while(($row = $result->fetch_assoc()) !== null) {

			//covert the associative array to a profileId and teamId and repeat for all banStatus
			try {
				$userTeam = new userTeam($row["profileId"], $row["teamId"], $row["roleInTeam"], $row["teamPermission"],
					$row["commentPermission"], $row["invitePermission"], $row["banStatus"]);
				//build empty array for sql to fill
				$banStatusArray [] = $userTeam;

			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));
			}
		}
		//if we get a banStatus I'm lucky and show it
		if ($result->num_rows ===0) {
			return (null);
		} else {

			return ($banStatusArray);
		}
	}



}