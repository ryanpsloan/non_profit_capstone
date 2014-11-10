<?php
/**
 * Created by PhpStorm.
 * User: dameo_000
 * Date: 7/11/2014
 * Time: 08:50
 */

class Event {
	private $eventId;

	private $eventTitle;

	private $eventDate;

	private $eventLocation;

	/**
	 * @param mixed $eventId product id (or null if new object)
	 * @param string $eventTitle event title
	 * @param string $eventDate the date of the event
	 * @param string $eventLocation where it is taking place
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
	/**
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
	 * @param string $newEventDate eventDate
	 * @throws UnexpectedValueException if date is not valid
	 */
	public function  setEventDate($newEventDate){
		$newEventDate = trim($newEventDate);
		if (($newEventDate = DateTime::createFromFormat("Y-m-d H:i:s", $newEventDate)) === false){
			throw(new UnexpectedValueException("The date $newEventDate is not a valid date."));
		}
		$this->eventDate = $newEventDate;
	}

	/**
	 * @param string $newEventLocation eventLocation
	 */
	public function setEventLocation($newEventLocation){
		$newEventLocation = trim($newEventLocation);
		$newEventLocation = filter_var($newEventLocation, FILTER_SANITIZE_STRING);

		$this->eventLocation = $newEventLocation;
	}

	/**
	 * @param resource $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */

	public function insert(&$mysqli)
	{
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

		$wasClean = $statement->bind_param("sss", $this->eventTitle, $this->eventDate, $this->eventLocation);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw (new mysqli_sql_exception("Unable to execute mySQL insert statement"));

			$this->eventId = $mysqli->insert_id;
		}

	}
	/**
	 *@param resource $mysqli pointer to mySQL connection by reference
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

	public function update($myslqi){

	}

}