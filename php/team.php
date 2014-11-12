<?php
class Team {
	/** Team ID **/
	private $teamId;
	/** Team  name **/
	private $teamName;
	/** Team description **/
	private $teamCause;

/**
 * constructor for Team
 *
 * @param mixed $newTeamId team id (or null if new object)
 * @param string $newTeamName
 * @param string $newTeamCause
 * @throws UnexpectedValueException when a parameter is of the wrong type
 * @throws RangeException when a parameter is invalid
 **/
	public function __construct($newTeamId, $newTeamName, $newTeamCause) {
		try {
			$this->setTeamId($newTeamId);
			$this->setTeamName($newTeamName);
			$this->setTeamCause($newTeamCause);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Team", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct Team", 0, $range));
		}
	}
/**
 * gets the value of teamId
 *
 * @return mixed teamId (or null if new object)
 **/
	public function getTeamId() {
		return($this->teamId);
	}
/**
 * sets the value of teamId
 *
 * @param mixed $newTeamId team id (or null if new object)
 * @throws UnexpectedValueException if not an integer or null
 * @throws RangeException if teamId isn't positive
 **/
	public function setTeamId($newTeamId) {
		if($newTeamId === null) {
			$this->teamId = null;
			return;
		}
		if(filter_var($newTeamId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Team Id $newTeamId is not numeric"));
		}
		$newTeamId = intval($newTeamId);
		if($newTeamId <= 0) {
			throw(new RangeException("team Id $newTeamId is not positive"));
		}
		$this->yeamd = $newTeamId;
	}
	public function insert(&$mysqli) {

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->teamId !== null) {
			throw(new mysqli_sql_exception("not a new team member"));
		}
		$query     = "INSERT INTO team(teamName, teamCause) VALUES(?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		$wasClean = $statement->bind_param("ss", $this->teamName, $this->teamCause);
      if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

      if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

      $this->teamId = $mysqli->insert_id;
   }
	public function delete(&$mysqli)	{

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->teamId === null) {
			throw(new mysqli_sql_exception("Unable to delete a team that does not exist"));
		}

		$query = "DELETE FROM team WHERE teamId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		$wasClean = $statement->bind_param("i", $this->teamId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	public function update($mysqli){
		//* query to find Team ID//
		$query = "UPDATE team SET teamName = ?, teamCause = ?, price = ? WHERE teamId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ssi", $this->teamName, $this->teamCause, $this->teamId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}
}

