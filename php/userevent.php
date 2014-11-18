<?php
/**
 * This will connect the user to his events.
 * This will serve as the intersection of event and user allowing for m to n relationship between events and users
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

require_once("../php/event.php");
require_once("../php/profile.php");


class userEvent {
	/**
	 * This will connect the user to the events
	 */
	private $profileId;
	/**
	 * This will connect the event to the users
	 */
	private $eventId;
	/**
	 * This will define the roll and permission a user has
	 */
	private $userEventRole;
	/**
	 * This will define the commenting permissions of a user
	 */
	private $commentPermission;
	/**
	 * This will define whether the user is banned or not
	 */
	private $banStatus;

	/**
	 * Creates the userEvent object
	 *
	 * @param mixed $newProfileId
	 * @param mixed $newEventId
	 * @param mixed $newUserEventRole sets the role of the user in event
	 * @param mixed $newCommentPermission sets users ability to comment
	 * @param mixed $newBanStatus sets whether the user is banned or not
	 */
	public function __construct($newProfileId,$newEventId, $newUserEventRole, $newCommentPermission, $newBanStatus){
		try{
			$this->setProfileId($newProfileId);

			$this->setEventId($newEventId);

			$this->setUserEventRole($newUserEventRole);

			$this->setCommentPermission($newCommentPermission);

			$this->setBanStatus($newBanStatus);
		} catch (UnexpectedValueException $unexpectedValue){
			throw(new UnexpectedValueException("Unable to construct object userEvent", 0 , $unexpectedValue));
		} catch (RangeException $rangeException){
			throw(new RangeException("Unable to construct object userEvent", 0, $rangeException));
		}
	}

	/**
	 * Sets the value of profileId from profile class
	 *
	 * @param mixed $newProfileId team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if Cause id is not positive
	 **/
	public function setProfileId($newProfileId)
	{
		if($this->profileId === null) {
			throw (new UnexpectedValueException("profileId does not exist"));
		}

		if(filter_var($newProfileId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("profileId $newProfileId is not numeric"));
		}

		$newProfileId = intval($newProfileId);
		if($newProfileId <= 0) {
			throw(new RangeException("teamId $newProfileId is not positive."));
		}

		$this->profileId = $newProfileId;
	}

	/**
	 * Sets the value of eventId from event class
	 *
	 * @param mixed $newEventId team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if Cause id is not positive
	 **/
	public function setEventId($newEventId){
		if($this->eventId === null) {
			throw (new UnexpectedValueException("eventId does not exist"));
		}

		if(filter_var($newEventId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("teamId $newEventId is not numeric"));
		}

		$newEventId = intval($newEventId);
		if($newEventId <= 0) {
			throw(new RangeException("eventId $newEventId is not positive."));
		}

		$this->eventId = $newEventId;
	}

	/**
	 * Sets the users role and status for an event
	 *
	 * @param $newUserEventRole
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if team status is not positive
	 */

	public function setUserEventRole($newUserEventRole)
	{
		if($newUserEventRole === null) {
			throw(new UnexpectedValueException("userEvent Roll Must not be Null"));
		}

		if(filter_var($newUserEventRole, FILTER_SANITIZE_INT) === false) {
			throw(new UnexpectedValueException("teamStatus $newUserEventRole is not numeric"));
		}

		if($newUserEventRole <= 0) {
			throw(new RangeException("Team status is not positive"));
		}

		$this->userEventRole = $newUserEventRole;
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
			throw (new UnexpectedValueException("commentPermission must not be null"));
		}

		if(filter_var($newCommentPermission, FILTER_SANITIZE_INT) === false) {
			throw(new UnexpectedValueException("Comment permission $$newCommentPermission is not numeric"));
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
			throw (new UnexpectedValueException("banStatus must not be null"));
		}

		if(filter_var($newBanStatus, FILTER_SANITIZE_INT) === false) {
			throw(new UnexpectedValueException("Comment permission $newBanStatus is not numeric"));
		}

		if($newBanStatus <= 0) {
			throw(new RangeException("Comment permission is not positive"));
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

		if($this->eventId === null){
			throw(new mysqli_sql_exception("EventId cannot be null"));
		}

		if($this->profileId === null){
			throw(new mysqli_sql_exception("ProfileId cannot be null"));
		}

		$query = "INSERT INTO userEvent(profileId, eventId, userEventRole, commentPermission, banStatus)
		VALUES (?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$wasClean = $statement->bind_param("iiiii", $this->profileId, $this->eventId, $this->userEventRole,
			$this->commentPermission, $this->banStatus);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false){
			throw(new mysqli_sql_exception("Unable to execute statement"));
		}
	}

	/**
	 * Deletes the objects from mySQL
	 *
	 * @param TeamCause $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function delete($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->profileId === null) {
			throw(new mysqli_sql_exception("Unable to delete a profile that does not exist"));
		}

		if($this->eventId === null){
			throw(new mysqli_sql_exception("Unable to delete an event that does not exist"));
		}

		$query		="DELETE FROM userEvent WHERE profileId = ? AND eventId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->profileId, $this->eventId);
		if($wasClean === false){
			throw (new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

	}

	/**
	 * Updates the values within mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->profileId === null){
			throw (new mysqli_sql_exception("Cannot update event object that does not exist"));
		}

		if($this->eventId === null){
			throw (new mysqli_sql_exception("cannot update team object that does not exist"));
		}

		$query		="UPDATE userEvent SET profileId = ?, eventId = ?, userEventRole = ?, commentPermission = ?, banStatus = ?";
		$statement  =$mysqli->prepare->$query;
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("iiiii", $this->eventId, $this->teamId, $this->teamStatus,
			$this->commentPermission, $this->banStatus);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute === false){
			throw(new mysqli_sql_exception("Unable to execute mySQL statement."));
		}
	}

	/**
	 * gets the mysqli object, creating it if necessary
	 *
	 * @return mysqli shared mysqli object
	 * @throws mysqli_sql_exception if the object cannot be created
	 **/
	public static function getUserEventByEventId($mysqli, $eventId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the eventId before searching
		$eventId = trim($eventId);
		$eventId = filter_var($eventId, FILTER_VALIDATE_INT);



		// create query template
		$query = "SELECT profileId,eventId, userEventRole, commentPermission, banStatus FROM userEvent WHERE eventId = ?";

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
				$userEvent = new UserEvent( $row["profileId"], $row["eventId"], $row["userEventRole"], $row["commentPermission"],
					$row["banStatus"]);
				$eventIdSearch [] = $userEvent;
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
}