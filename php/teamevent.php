<?php
/**
 * This is the class that will connect event and team and allow them to draw permissions from eachother
 * Connecting class for event and team
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

require_once("../php/event.php");
require_once("../php/team.php");


class TeamEvent {
	/**
	 * The id and foreign key for team
	 */
	private $teamId;
	/**
	 * The id and foreign key for Event
	 */
	private $eventId;
	/**
    * The status for the team, whether the team is the creator or not
	 */
	private $teamStatus;
	/**
	 * The comment permissions teams have within events
	 */
	private $commentPermission;
	/**
	 * The ban status of a team within an event
	 */
	private $banStatus;

	/**
	 * Creates the TeamEvent object
	 *
	 * @param mixed $newTeamId team id (or null if new object)
	 * @param mixed $newEventId event id (or null if new object)
	 * @param mixed $newTeamStatus teams role in event
	 * @param mixed $newCommentPermission the permission to comment
	 * @param mixed $newBanStatus whether team is banned in event or not
	 **/
	public function __construct($newTeamId, $newEventId, $newTeamStatus, $newCommentPermission, $newBanStatus)
	{
		try {
			$this->setTeamId($newTeamId);
			$this->setEventId($newEventId);
			$this->setTeamStatus($newTeamStatus);
			$this->setCommentPermission($newCommentPermission);
			$this->setBanStatus($newBanStatus);
		} catch(UnexpectedValueException $unexpectedValue) {
			throw(new UnexpectedValueException("Could not construct object TeamEvent", 0, $unexpectedValue));
		} catch(RangeException $range) {
			throw(new RangeException("Could not construct object TeamEvent", 0, $range));
		}
	}


	// The function to call the parameters of this class
	public function __get($name)
	{
		$data = array("teamId"        		=> $this->teamId,
						  "eventId"       		=> $this->eventId,
						  "teamStatus"    		=> $this->teamStatus,
						  "commentPermission" 	=> $this->commentPermission,
						  "banStatus" 				=> $this->banStatus);
		if(array_key_exists($name, $data)) {
			return $data[$name];
		} else {
			throw(new InvalidArgumentException("Unable to get $name"));
		}
	}

	/**
	 * @param mixed $newTeamId team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if event id is not positive
	 **/
	public function setTeamId($newTeamId)
	{
		if($newTeamId === null) {
			throw(new UnexpectedValueException("Team Id is null"));
		}

		if(filter_var($newTeamId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("teamId $newTeamId is not numeric"));
		}

		$newTeamId = intval($newTeamId);
		if($newTeamId <= 0) {
			throw(new RangeException("teamId $newTeamId is not positive."));
		}
		$this->teamId = $newTeamId;
	}

	/**
	 * Sets the event id within the object
	 *
	 * @param mixed $newEventId event id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if event id is not positive
	 **/
	public function setEventId($newEventId)
	{
		if($newEventId === null) {
			throw(new UnexpectedValueException("Event Id does not exist"));
		}

		if(filter_var($newEventId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("eventId $newEventId is not numeric."));
		}

		if($newEventId <= 0) {
			throw(new RangeException("eventId is not positive."));
		}

		$this->eventId = $newEventId;
	}

	/**
	 * Sets the teams role and status for an event
	 *
	 * @param $newTeamStatus
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if team status is not positive
	 */

	public function setTeamStatus($newTeamStatus)
	{
		if($newTeamStatus === null) {
			throw(new UnexpectedValueException("Team Status cannot be null"));
		}

		if(filter_var($newTeamStatus, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("teamStatus $newTeamStatus is not numeric"));
		}

		if($newTeamStatus <= 0) {
			throw(new RangeException("Team status is not positive"));
		}

		$this->teamStatus = $newTeamStatus;
	}

	/**
	 * Sets the ability for a team to comment on an event
	 *
	 * @param $newCommentPermission
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if team status is not positive
	 */

	public function setCommentPermission($newCommentPermission)
	{
		if($newCommentPermission === null) {
			throw(new UnexpectedValueException("Comment permission $newCommentPermission cannot by null"));
		}

		if(filter_var($newCommentPermission, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Comment permission $newCommentPermission is not numeric"));
		}

		if($newCommentPermission <= 0) {
			throw(new RangeException("Comment permission is not positive"));
		}

		$this->commentPermission = $newCommentPermission;
	}

	/**
	 * Sets whether the user is banned or not
	 *
	 * @param mixed $newBanStatus sets whether the user is banned or not
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if team status is not positive
	 */
	public function setBanStatus($newBanStatus)
	{
		if($newBanStatus === null) {
			throw(new UnexpectedValueException("Ban Status Cannot be Null"));
		}

		if(filter_var($newBanStatus, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Ban status $newBanStatus is not numeric"));
		}

		if($newBanStatus <= 0) {
			throw(new RangeException("Ban status is not positive"));
		}

		$this->banStatus = $newBanStatus;
	}

	/**
	 * Inserts the values in to mySQL
	 *
	 * @param TeamEvent $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function insert(&$mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw(new mysqli_sql_exception("This is not a valid mysqli object"));
		}

		if($this->teamId === null){
			throw(new mysqli_sql_exception("This team does not exist"));
		}

		if($this->eventId === null){
			throw(new mysqli_sql_exception("This event does not exist"));
		}

		$query = "INSERT INTO teamEvent(teamId, eventId, teamStatus, commentPermission, banStatus)
		VALUES (?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$wasClean = $statement->bind_param("iiiii", $this->teamId, $this->eventId, $this->teamStatus,
			$this->commentPermission, $this->banStatus);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false){
			throw(new mysqli_sql_exception("Unable to execute statement"));
		}
	}

	/**
	 * Deletes the object from mySQL
	 *
	 * @param TeamEvent $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 **/
	public function delete($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->eventId === null) {
			throw(new mysqli_sql_exception("Unable to delete an event that does not exist"));
		}

		if($this->teamId === null) {
			throw(new mysqli_sql_exception("Unable to delete a team that does not exist"));
		}

		$query		="DELETE FROM teamEvent WHERE teamId = ? AND  eventId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->teamId, $this->eventId);
		if($wasClean === false){
			throw (new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}


	}

	/**
	 * Updates the object within mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->eventId === null){
			throw (new mysqli_sql_exception("Cannot update event object that does not exist"));
		}

		if($this->teamId === null){
			throw (new mysqli_sql_exception("cannot update team object that does not exist"));
		}

		$query		="UPDATE teamEvent SET teamId = ?, eventId = ?, teamStatus = ?, commentPermission = ?, banStatus = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("iiiii", $this->teamId, $this->eventId, $this->teamStatus,
			$this->commentPermission, $this->banStatus);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false){
			throw(new mysqli_sql_exception("Unable to execute mySQL statement."));
		}
	}

	/**
	 * gets the mysqli object using the EventId, creating it if necessary
	 *
	 * @param $mysqli mysqli object
	 * @param mixed $eventId
	 * @return array|null
	 * @throw mysqli_sql_exception if unable to properly execute statement
	 */
	public static function getTeamEventByEventId($mysqli, $eventId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the eventId before searching
		$eventId = trim($eventId);
		$eventId = filter_var($eventId, FILTER_VALIDATE_INT);



		// create query template
		$query = "SELECT teamId, eventId, teamStatus, commentPermission, banStatus FROM teamEvent WHERE eventId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the eventId to the place holder in the template
		$wasClean = $statement->bind_param("i", $eventId);
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
		$eventIdSearch = array();

		// Loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$teamEvent = new TeamEvent($row["teamId"], $row["eventId"], $row["teamStatus"], $row["commentPermission"],
				$row["banStatus"]);
				$eventIdSearch [] = $teamEvent;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to event", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($eventIdSearch);
		}

	}

	public static function getTeamEventByTeamId($mysqli, $teamId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the eventId before searching
		$teamId = trim($teamId);
		$teamId = filter_var($teamId, FILTER_VALIDATE_INT);
		if($teamId === null){
			throw(new UnexpectedValueException("teamId cannot be null"));
		}


		// create query template
		$query = "SELECT teamId, eventId, teamStatus, commentPermission, banStatus FROM teamEvent WHERE teamId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the eventId to the place holder in the template
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

		//turn the results into an array
		$teamIdSearch = array();

		// Loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$teamEvent = new TeamEvent($row["teamId"], $row["eventId"], $row["teamStatus"], $row["commentPermission"],
					$row["banStatus"]);
				$teamIdSearch [] = $teamEvent;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to event", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($teamIdSearch);
		}

	}
}