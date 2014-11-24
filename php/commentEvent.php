<?php
/**
 * Table that will link events and comments through the eventId and the CommentId
 * Link between events and comments
 * 
 * @author Dameon Smith
 */

require_once("../php/event.php");
require_once("../php/comment.php");

class CommentEvent {
	/**
	 * Primary key that links the event to this table
	 */
	private $eventId;
	/**
	 * Primary key that link the comments to this table
	 */
	private $commentId;

	public function __construct($newEventId, $newCommentId){
		try{
			$this->setEventId($newEventId);

			$this->setCommentId($newCommentId);
		} catch (UnexpectedValueException $unexpectedValue){
			var_dump($unexpectedValue->error);
			throw(new UnexpectedValueException("Unable to construct commentEvent object", 0, $unexpectedValue));
		} catch (RangeException $rangeException){
			throw(new RangeException("Unable to construct commentEvent object", 0, $rangeException));
		}
	}

	public function __get($name) {
		$data = array("eventId" => $this->eventId,
						  "commentId" => $this->commentId);
		if(array_key_exists($name, $data)) {
			return $data[$name];
		} else {
			throw(new InvalidArgumentException("Unable to get $name"));
		}
	}

	/**
	 * Sets the value of eventId from event class
	 *
	 * @param mixed $newEventId event id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if Cause id is not positive
	 **/
	public function setEventId($newEventId)
	{

		if(filter_var($newEventId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("eventId $newEventId is not numeric"));
		}

		$newEventId = intval($newEventId);
		if($newEventId <= 0) {
			throw(new RangeException("eventId $newEventId is not positive."));
		}

		$this->eventId = $newEventId;
	}

	/**
	 * assigns the value for commentId
	 *
	 * @param mixed $newCommentId comment id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if comment id is not positive
	 **/
	public function setCommentId($newCommentId){

		if(filter_var($newCommentId, FILTER_VALIDATE_INT) === false){
			throw(new UnexpectedValueException("commentId $newCommentId is not numeric"));
		}

		$newCommentId = intval($newCommentId);
		if($newCommentId <= 0) {
			throw(new RangeException("commentId $newCommentId is not positive"));
		}

		$this->commentId = $newCommentId;
	}

	/**
	 * Inserts the values in to mySQL
	 *
	 * @param CommentEvent $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function insert(&$mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw(new mysqli_sql_exception("This is not a valid mysqli object"));
		}

		if($this->eventId === null){
			throw(new mysqli_sql_exception("This event does not exist"));
		}

		if($this->commentId === null){
			throw(new mysqli_sql_exception("This comment does not exist"));
		}

		$query = "INSERT INTO commentEvent(eventId, commentId)
		VALUES (?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->eventId, $this->commentId);

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
	 * @param CommentEvent $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 **/
	public function delete($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->eventId === null) {
			throw(new mysqli_sql_exception("Unable to delete a event that does not exist"));
		}

		if($this->commentId === null) {
			throw(new mysqli_sql_exception("Unable to delete a comment that does not exist"));
		}

		$query		="DELETE FROM commentEvent WHERE eventId = ? AND commentId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->eventId, $this->commentId);
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
			throw (new mysqli_sql_exception("cannot update event object that does not exist"));
		}

		if($this->commentId === null){
			throw (new mysqli_sql_exception("Cannot update comment object that does not exist"));
		}

		$query		="UPDATE commentEvent SET eventId = ?, commentId = ?";
		$statement  =$mysqli->prepare->$query;
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->eventId, $this->commentId);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute === false){
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
	public static function getCommentEventByEventId($mysqli, $eventId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the eventId before searching
		$eventId = trim($eventId);
		$eventId = filter_var($eventId, FILTER_VALIDATE_INT);



		// create query template
		$query = "SELECT eventId, commentId FROM commentEvent WHERE eventId = ?";

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
				$commentEvent = new CommentEvent($row["eventId"], $row["commentId"]);
				$eventIdSearch [] = $commentEvent;
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

	/**
	 * gets the mysqli object using the CommentId, creating it if necessary
	 *
	 * @param $mysqli mysqli object
	 * @param mixed $commentId
	 * @return array|null
	 * @throw mysqli_sql_exception if unable to properly execute statement
	 */
	public static function getCommentEventByCommentId($mysqli, $commentId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the eventId before searching
		$commentId = trim($commentId);
		$commentId = filter_var($commentId, FILTER_VALIDATE_INT);



		// create query template
		$query = "SELECT eventId, commentId FROM commentEvent WHERE commentId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the eventId to the place holder in the template
		$wasClean = $statement->bind_param("i", $commentId);
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
		$commentIdSearch = array();

		// Loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$commentEvent = new CommentEvent($row["eventId"], $row["commentId"]);
				$commentIdSearch [] = $commentEvent;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to comment", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($commentIdSearch);
		}

	}

	/**
	 * gets the CommentEvent by eventId and commentId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $eventId and $commentId  eventId and commentId to search for
	 * @return int event and comment found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/


	public static function getCommentEventByEventCommentId(&$mysqli, $eventId, $commentId) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the eventId and commentId before searching
		$eventId = filter_var($eventId, FILTER_VALIDATE_INT);
		if($eventId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		$commentId = filter_var($commentId, FILTER_VALIDATE_INT);
		if($commentId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT eventId, commentId FROM commentEvent WHERE eventId = ? AND commentId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the profileId and teamId to the place holder in the template
		$wasClean = $statement->bind_param("ii", $eventId, $commentId);
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

		//covert the associative array to a commentEvent
		if($row !== null) {
			try {
				$commentEvent = new commentEvent($row["eventId"], $row["commentId"]);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));

			}
			//if we get a profileId and commentId I'm lucky and show it
			return ($commentEvent);
		} else {
			//404 User not found
			return (null);
		}
	}

}