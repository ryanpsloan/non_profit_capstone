<?php
/**
 * Class to create events.
 * This class will manage the creation of events in which the event can join, this is a m to n relationship through the
 * eventEvent intersection table.
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

class Event {
	/**
	* This is the id for the event and is the primary key.
	*/
	private $eventId;
	/**
	* This is the event title showing what the event is called
	*/
	private $eventTitle;
	/**
	* This is the event date that will display the date and time that the event is happening
	*/
	private $eventDate;
	/**
	 * This is the event location and will record where the event is happening
	 */
	private $eventLocation;

	/**
	 * Creates the Event object
	 *
	 * @param mixed $newEventId event id (or null if new object)
	 * @param string $newEventTitle event title
	 * @param string $newEventDate the date of the event
	 * @param string $newEventLocation where it is taking place
	 **/
	public function __construct($newEventId, $newEventTitle, $newEventDate, $newEventLocation){
		try {
			$this->setEventId($newEventId);
			$this->setEventTitle($newEventTitle);
			$this->setEventDate($newEventDate);
			$this->setEventLocation($newEventLocation);
		} catch(UnexpectedValueException $unexpectedValue) {
			throw(new UnexpectedValueException("Unable to construct Event", 0, $unexpectedValue));
		} catch(RangeException $range){
			throw(new RangeException("Unable to construct Event", 0, $range));
		}
	}

	public function __get($name) {
		$data = array("eventId" => $this->eventId,
						  "eventTitle" => $this->eventTitle,
						  "eventDate" => $this->eventDate,
						  "eventLocation" => $this->eventLocation);
		if(array_key_exists($name, $data)) {
			return $data[$name];
		} else {
			throw(new InvalidArgumentException("Unable to get $name"));
		}
	}

	/**
	 * assigns the value for eventId
	 *
	 * @param mixed $newEventId event id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if event id is not positive
	 **/
	public function setEventId($newEventId){
		if($newEventId === null){
			$this->eventId = null;
			return;
		}

		if(filter_var($newEventId, FILTER_VALIDATE_INT) === false){
			throw(new UnexpectedValueException("eventId $newEventId is not numeric"));
		}

		$newEventId = intval($newEventId);
		if($newEventId <= 0) {
			throw(new RangeException("eventId $newEventId is not positive"));
		}

		$this->eventId = $newEventId;
	}

	public function setEventTitle($newEventTitle){
		$newEventTitle = trim($newEventTitle);
		$newEventTitle = filter_var($newEventTitle, FILTER_SANITIZE_STRING);

		$this->eventTitle = $newEventTitle;
	}
	
	/**
	 * sets the value of date created
	 *
	 * @param mixed $newEventDate object or string with the date created
	 * @throws RangeException if date is not a valid date
	 **/
	public function setEventDate($newEventDate) {
		// zeroth, allow a DateTime object to be directly assigned
		if(gettype($newEventDate) === "object" && get_class($newEventDate) === "DateTime") {
			$this->eventDate = $newEventDate;
			return;
		}

		// treat the date as a mySQL date string
		$newEventDate = trim($newEventDate);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newEventDate, $matches)) !== 1) {
			throw(new RangeException("$newEventDate is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year  = intval($matches[1]);
		$month = intval($matches[2]);
		$day   = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("$newEventDate is not a Gregorian date"));
		}

		// finally, take the date out of quarantine
		$newEventDate = DateTime::createFromFormat("Y-m-d H:i:s", $newEventDate);
		$this->eventDate = $newEventDate;
	}


	/**
	 * sets the value of EventLocation in the table
	 *
	 * @param string $newEventLocation eventLocation
	 */
	public function setEventLocation($newEventLocation){
		$newEventLocation = trim($newEventLocation);
		$newEventLocation = filter_var($newEventLocation, FILTER_SANITIZE_STRING);

		// todo add the exception for Locations that are too long

		$this->eventLocation = $newEventLocation;
	}

	/**
	 * Inserts the event object into mysql
	 *
	 * @param event $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function insert(&$mysqli)	{
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->eventId !== null) {
			throw(new mysqli_sql_exception("Not a new event"));
		}

		$query = "INSERT INTO event(eventTitle, eventDate, eventLocation) VALUES(?,?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$dateString = $this->eventDate->format("Y-m-d H:i:s");

		$wasClean = $statement->bind_param("sss", $this->eventTitle, $dateString, $this->eventLocation);

		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw (new mysqli_sql_exception("Unable to execute mySQL insert statement"));
		}

		$this->eventId = $mysqli->insert_id;
	}

	/**
	 * deletes the event object from mysql by grabbing the event Id
	 *
	 *@param event $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occur
	 */
	public function delete($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->eventId === null) {
			throw(new mysqli_sql_exception("Unable to delete an event that does not exist"));
		}

		$query		="DELETE FROM event WHERE eventId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("i", $this->eventId);
		if($wasClean === false){
			throw (new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates the objects values in mysql
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->eventId === null){
			throw (new mysqli_sql_exception("Cannot update object that does not exist"));
		}

		// Convert date to strings
		if($this->eventDate === null) {
			$dateCreated = null;
		} else {
			$eventDate = $this->eventDate->format("Y-d-m H:i:s");
		}

		$query		="UPDATE event SET eventId = ?, eventTitle = ?, eventDate = ?, eventLocation = ?";
		$statement  =$mysqli->prepare->$query;
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("isss", $this->eventId, $this->eventTitle,
																$this->eventDate, $this->eventLocation);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute === false){
			throw(new mysqli_sql_exception("Unable to execute mySQL statement."));
		}
	}

	/**
	 * gets the mysqli object using the eventId, creating it if necessary
	 *
	 * @param $mysqli
	 * @param mixed $eventId
	 * @return mysqli shared mysqli object
	 * @throws mysqli_sql_exception if the object cannot be created
	 **/
	public static function getEventByEventId(&$mysqli, $eventId) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the EventDate before searching
		$eventId = trim($eventId);
		$eventId = filter_var($eventId, FILTER_VALIDATE_INT);

		// create query template
		$query = "SELECT eventId, eventTitle, eventDate, eventLocation FROM event WHERE eventId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the email to the place holder in the template
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


		$row = $result->fetch_assoc();

		// convert the associative array to a Event
		if($row !== null) {
			try {
				$event = new Event($row["eventId"], $row["eventTitle"],
					$row["eventDate"], $row["eventLocation"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Event", 0, $exception));
			}

			// if we got here, the Event is good - return it
			return($event);
		} else {
			// 404 Event not found - return null instead
			return(null);
		}
	}

	/**
	 * gets the mysqli object using the event title, creating it if necessary
	 *
	 * @param $mysqli
	 * @param mixed $eventTitle
	 * @return mysqli shared mysqli object
	 * @throws mysqli_sql_exception if the object cannot be created
	 **/
	public static function getEventByEventTitle(&$mysqli, $eventTitle) {

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$eventTitle = trim($eventTitle);
		$eventTitle = filter_var($eventTitle, FILTER_SANITIZE_STRING);

		$query = "SELECT eventId, eventTitle, eventDate, eventLocation FROM event WHERE eventTitle = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("s", $eventTitle);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set."));
		}

		$eventTitleSearch = array();

		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$event = new Event($row["eventId"], $row["eventTitle"], $row["eventDate"],
					$row["eventLocation"]);
				$eventTitleSearch [] = $event;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to event", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($eventTitleSearch);
		}
	}

	/**
	 * gets the mysqli object by finding the date, creating it if necessary
	 *
	 * @param $mysqli
	 * @param mixed $eventDate
	 * @return mysqli shared mysqli object
	 * @throws mysqli_sql_exception if the object cannot be created
	 **/
	public static function getEventByEventDate(&$mysqli, $eventDate) {

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$eventDate = trim($eventDate);
		$eventDate = filter_var($eventDate, FILTER_SANITIZE_STRING);

		$query = "SELECT eventId, eventTitle, eventDate, eventLocation FROM event WHERE eventDate = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("s", $eventDate);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set."));
		}

		$eventDateSearch = array();

		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$event = new Event($row["eventId"], $row["eventDate"], $row["eventTitle"],
					$row["eventLocation"]);
				$eventDateSearch [] = $event;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to event", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($eventDateSearch);
		}
	}

	/**
	 * gets the mysqli object by finding the location, creating it if necessary
	 *
	 * @param $mysqli
	 * @param $eventLocation
	 * @return mysqli shared mysqli object
	 * @throws mysqli_sql_exception if the object cannot be created
	 **/
	public static function getEventByEventLocation(&$mysqli, $eventLocation){
		// handle the degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		// sanitize the inputs
		$eventLocation = trim($eventLocation);
		$eventLocation = filter_var($eventLocation, FILTER_SANITIZE_STRING);
		// create the query template
		$query = "SELECT eventId, eventTitle, eventDate, eventLocation FROM event WHERE eventLocation = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("s", $eventLocation);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set."));
		}

		// turn the results into an array
		$eventLocationSearch = array();

		// Loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$event = new Event($row["eventId"], $row["profileId"], $row["date"], $row["eventDate"], $row["eventTitle"],
					$row["eventBody"]);
				$eventLocationSearch [] = $event;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to event", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($eventLocationSearch);
		}
	}



}