<?php
class Cause {
	/** cause ID **/
	private $causeId;
	/** cause  name **/
	private $causeName;
	/** cause description **/
	private $causeDescription;

	/**
	 * constructor for cause
	 *
	 * @param mixed $newCauseId cause id (or null if new object)
	 * @param string $newCauseName
	 * @param string $newCauseDescription
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/
	public function __construct($newCauseId, $newCauseName, $newCauseDescription) {
		try {
			$this->setCauseId($newCauseId);
			$this->setCauseName($newCauseName);
			$this->setCauseDescription($newCauseDescription);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Cause", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct Cause", 0, $range));
		}
	}
	/**
	 * gets the value of causeId
	 *
	 * @return mixed causeId (or null if new object)
	 **/
	public function getCauseId() {
		return($this->causeId);
	}
	/**
	 * sets the value of causeId
	 *
	 * @param mixed $newCauseId cause id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if causeId isn't positive
	 **/
	public function setCauseId($newCauseId) {
		if($newCauseId === null) {
			$this->causeId = null;
			return;
		}
		if(filter_var($newCauseId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Cause Id $newCauseId is not numeric"));
		}
		$newCauseId = intval($newCauseId);
		if($newCauseId <= 0) {
			throw(new RangeException("Cause Id $newCauseId is not positive"));
		}
		$this->causeId = $newCauseId;
	}

	/**
	 * sets the value of CauseName
	 *
	 * @param mixed $newCauseName cause  id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if causeId isn't positive
	 **/

	public function getCauseName() {
		return($this->causeName);
	}
	/**
	 * creates a new CauseName
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $causeId causeId to search for
	 * @return mixed causeId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function setCauseName ($newCauseName){
		if($newCauseName === null){
			throw(new UnexpectedValueException("CauseName cannot be null"));
		}

		$newCauseName = trim($newCauseName);
		if(filter_var($newCauseName, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("CauseName $newCauseName is not a string"));
		}
		//verify CauseName is less than 257 characters
		$newCauseNameLength = strlen($newCauseName);
		if($newCauseNameLength > 257) {
			throw(new RangeException("Cause name $newCauseName is longer than 256 characters"));
		}
		//remove CauseName from quarantine
		$this->causeName = $newCauseName;
	}
	/**
	 * sets the value of CauseName
	 *
	 * @param mixed $newCauseName cause  id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if causeId isn't positive
	 **/

	public function getCauseDescription() {
		return($this->causeDescription);
	}
	/**
	 * creates a new CauseName
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $causeId causeId to search for
	 * @return mixed causeId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function setCauseDescription ($newCauseDescription){
		if($newCauseDescription === null){
			throw(new UnexpectedValueException("CauseName cannot be null"));
		}

		$newCauseDescription = trim($newCauseDescription);
		if(filter_var($newCauseDescription, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("CauseName $newCauseDescription is not a string"));
		}
		//verify CauseName is less than 257 characters
		$newCauseDescriptionLength = strlen($newCauseDescription);
		if($newCauseDescriptionLength > 257) {
			throw(new RangeException("Cause name $newCauseDescription is longer than 256 characters"));
		}
		//remove CauseName from quarantine
		$this->causeDescription = $newCauseDescription;
	}

	/**
	 * Inserts a new Cause
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $causeId causeId to search for
	 * @return mixed causeId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public function insert(&$mysqli) {

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->causeId !== null) {
			throw(new mysqli_sql_exception("not a new cause"));
		}
		$query     = "INSERT INTO cause(causeName, causeDescription) VALUES(?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		$wasClean = $statement->bind_param("ss", $this->causeName, $this->causeDescription);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$this->causeId = $mysqli->insert_id;
	}
	/**
	 * deletes a Cause
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $causeId causeId to search for
	 * @return mixed causeId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/


	public function delete(&$mysqli)	{

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->causeId === null) {
			throw(new mysqli_sql_exception("Unable to delete a cause that does not exist"));
		}

		$query = "DELETE FROM cause WHERE causeId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		$wasClean = $statement->bind_param("i", $this->causeId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}
	/**
	 * updates the Cause
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $causeId causeId to search for
	 * @return mixed causeId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public function update($mysqli){
		//* query to find Cause ID//

		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the causeId is not null (i.e., don't update a cause that hasn't been inserted)
		if($this->causeId === null) {
			throw(new mysqli_sql_exception("Unable to update a cause that does not exist"));
		}

		$query = "UPDATE cause SET causeName = ?, causeDescription = ? WHERE causeId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ssi", $this->causeName, $this->causeDescription, $this->causeId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}
	/**
	 * gets the Cause by CauseId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $causeId causeId to search for
	 * @return mixed causeId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public static function getCauseByCauseId(&$mysqli, $causeId)
	{
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the causeId before searching
		$causeId = filter_var($causeId, FILTER_VALIDATE_INT);
		if($causeId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT causeId, causeName, causeDescription FROM cause WHERE causeId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the cause Id to the place holder in the template
		$wasClean = $statement->bind_param("i", $causeId);
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

		//covert the associative array to a CauseId
		if($row !== null) {
			try {
				$cause = new cause($row["causeId"], $row["causeName"], $row["causeDescription"]);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to cause", 0, $exception));
			}
			//if we get a causeId I'm lucky and show it
			return ($cause);
		} else {
			//404 User not found
			return (null);
		}
	}

	/**
	 * gets the Cause by causeName
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $causeName causeName to search for
	 * @return mixed causeName found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public static function getCauseByCauseName(&$mysqli, $causeName)
	{
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//sanitize the causeName before searching
		$causeName = trim($causeName);
		$causeName = filter_var($causeName, FILTER_SANITIZE_STRING);
		if($causeName === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT causeId, causeName, causeDescription FROM cause WHERE causeName LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the cause Id to the place holder in the template
		$causeName = "%$causeName%";
		$wasClean = $statement->bind_param("s", $causeName);
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
		//Unique can only one of two things null or string
		//if there's a result, we can show it

		//causeArrayCounter = 0
		$causeArray = array();
		while(($row = $result->fetch_assoc()) !== null) {

			//covert the associative array to a causeId and repeat for all causeNames
			try {
				$cause = new cause($row["causeId"], $row["causeName"], $row["causeDescription"]);
				//build empty array for sql to fill
				$causeArray [] = $cause;

			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to Cause", 0, $exception));
			}
		}
		//if we get a causeId I'm lucky and show it
		if ($result->num_rows ===0) {
			return (null);
		} else {

			return ($causeArray);
		}
	}

}