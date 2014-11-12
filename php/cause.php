<?php
/**
 * Created by PhpStorm.
 * User: Cass
 * Date: 11/7/2014
 * Time: 11:01 AM
 */
class Cause{
	private $causeId;
	/** cause name */
	private $causeName;
	/** Cause description **/
	private $causeDescription;

/**
 * constructor for Cause
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
	/** get the cause ID */
	public function getCauseId() {
		return($this->causeId);
	}
	public function setCauseId($newCauseId) {
		if($newCauseId === null) {
			$this->causeId = null;
			return;
		}
		if(filter_var($newCauseId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("CauseId id $newCauseId is not numeric"));
		}
		$newCauseId = intval($newCauseId);
		if($newCauseId <= 0) {
			throw(new RangeException("CauseId $newCauseId is not positive"));
		}
		$this->causeId = $newCauseId;
	}
	public function insert(&$mysqli) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		if($this->causeId !== null) {
			throw(new mysqli_sql_exception("not a new cause"));
		}
		$query     = "INSERT INTO cause(causeId, causeName, causeDescription) VALUES(?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		$wasClean = $statement->bind_param("ssi", $this->causeName, $this->causeDescription,$this->causeId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
		$this->causeId = $mysqli->insert_id;
	}
	public function delete(&$mysqli) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		if($this->causeId === null) {
			throw(new mysqli_sql_exception("Unable to delete a cause that does not exist"));
		}
		$query     = "DELETE FROM cause WHERE causeId = ?";
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
	public function update(&$mysqli) {
			// handle degenerate cases
		if(gettype($mysqli) !== "cause" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
			}
			// enforce the CauseId is not null (i.e., don't update a user that hasn't been inserted)
		if($this->causeId === null) {
			throw(new mysqli_sql_exception("Unable to update a cause that does not exist"));
			}
		$query     = "UPDATE cause SET causeName = ?, CauseDescription = ?, WHERE causeId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
			}
		$wasClean = $statement->bind_param("ssi",  $this->causeName, $this->caauseDescriptionCause, $this->causeId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}
}

/**
 * Created by PhpStorm.
 * User: Cass
 * Date: 11/7/2014
 * Time: 11:02 AM
 */