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
		$this->teamId = $newTeamId;
	}

	/**
	 * sets the value of TeamName
	 *
	 * @param mixed $newTeamName team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if teamId isn't positive
	 **/

	public function getTeamName() {
		return($this->teamName);
	}
	/**
	 * creates a new TeamName
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $teamId teamId to search for
	 * @return mixed teamId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function setTeamName ($newTeamName){
		if($newTeamName === null){
			throw(new UnexpectedValueException("TeamName cannot be null"));
		}

		$newTeamName = trim($newTeamName);
		if(filter_var($newTeamName, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("TeamName $newTeamName is not a string"));
		}
		//verify TeamName is less than 64 characters
		$newTeamNameLength = strlen($newTeamName);
		if($newTeamNameLength > 65) {
			throw(new RangeException("Team name $newTeamName is longer than 64 characters"));
		}
		//remove TeamName from quarantine
		$this->teamName = $newTeamName;
	}

	/**
	 * sets the value of TeamCause
	 *
	 * @param mixed $newTeamCause team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if teamId isn't positive
	 **/

	public function getTeamCause() {
		return($this->teamCause);
	}

	public function setTeamCause ($newTeamCause){
		if($newTeamCause === null){
			throw(new UnexpectedValueException("TeamCause cannot be null"));
		}

		$newTeamCause = trim($newTeamCause);
		if(filter_var($newTeamCause, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("TeamCause $newTeamCause is not a string"));
		}
		//verify TeamName is less than 64 characters
		$newTeamCauseLength = strlen($newTeamCause);
		if($newTeamCauseLength > 65) {
			throw(new RangeException("Team cause $newTeamCause is longer than 64 characters"));
		}
		//remove UserTitle from quarantine
		$this->teamCause = $newTeamCause;
	}

	/**
	 * Inserts a new Team
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return mixed teamId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

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
	/**
	 * deletes a Team
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $teamId teamId to search for
	 * @return mixed teamId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/


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
	/**
	 * updates the Team
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @return mixed teamId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public function update($mysqli){
		//* query to find Team ID//

		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the teamId is not null (i.e., don't update a team that hasn't been inserted)
		if($this->teamId === null) {
			throw(new mysqli_sql_exception("Unable to update a team that does not exist"));
		}

		$query = "UPDATE team SET teamName = ?, teamCause = ? WHERE teamId = ?";
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
	/**
	 * gets the Team by TeamId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $teamId teamId to search for
	 * @return mixed teamId found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public static function getTeamByTeamId(&$mysqli, $teamId)
	{
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the teamId before searching
		$teamId = filter_var($teamId, FILTER_VALIDATE_INT);
		if($teamId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT teamId, teamName, teamCause FROM team WHERE teamId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the team Id to the place holder in the template
		$wasClean = $statement->bind_param("i", $teamId);
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

		//covert the associative array to a TeamId
		if($row !== null) {
			try {
				$team = new team($row["teamId"], $row["teamName"], $row["teamCause"]);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to team", 0, $exception));

			}
			//if we get a teamId I'm lucky and show it
			return ($team);
		} else {
			//404 User not found
			return (null);
		}
	}

	/**
	 * gets the Team by teamName
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $teamName teamName to search for
	 * @return mixed teamName found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public static function getTeamByTeamName(&$mysqli, $teamName)
	{
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		//sanitize the teamName before searching
		$teamName = trim($teamName);
		$teamName = filter_var($teamName, FILTER_SANITIZE_STRING);
		if($teamName === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT teamId, teamName, teamCause FROM team WHERE teamName LIKE ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the team Id to the place holder in the template
		$teamName = "%$teamName%";
		$wasClean = $statement->bind_param("s", $teamName);
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
		//if not error code 404
		$row = $result->fetch_assoc();

		//covert the associative array to a TeamId
		if($row !== null) {
			try {
				$team = new team($row["teamId"], $row["teamName"], $row["teamCause"]);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to team", 0, $exception));

			}
			//if we get a teamId I'm lucky and show it
			return ($team);
		} else {
			//404 User not found
			return (null);
		}
	}
	/**
	 * gets the Team by teamCause
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $teamCause teamCause to search for
	 * @return string teamCause found or null if not found
 	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getTeamByTeamCause(&$mysqli, $teamCause) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the teamCause before searching
		$teamCause = trim($teamCause);
		$teamCause = filter_var($teamCause, FILTER_SANITIZE_STRING);
		if($teamCause === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT teamId, teamName, teamCause FROM team WHERE teamCause = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the teamId to the place holder in the template
		$wasClean = $statement->bind_param("s", $teamCause);
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

		//many teams can have different teamCauses
		//if there's a result, we can show it
		//if not error code 404

		//teamArrayCounter = 0
		$teamCauseArray = array();
		while(($row = $result->fetch_assoc()) !== null) {

			//covert the associative array to a teamId and repeat for all teamNames
			try {
				$team = new team($row["teamId"], $row["teamName"], $row["teamCause"]);
				//build empty array for sql to fill
				$teamCauseArray [] = $team;

			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to Team", 0, $exception));
			}
		}
		//if we get a teamId I'm lucky and show it
		if ($result->num_rows ===0) {
			return (null);
		} else {

			return ($teamCauseArray);
		}
	}




}


