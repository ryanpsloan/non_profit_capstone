<?php
class Team
{
	private $teamId;
	/** Team  name */
	private $teamName;
	/** Team description **/
	private $teamCause;


	public function __construct($newTeamId, $newTeamName, $newTeamCause)
	{
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

	public function getTeamId()
	{
		return ($this->teamId);
	}

	public function setTeamId($newTeamId)
	{
		if($newTeamId === null) {
			$this->teamId = null;
			return;
		}
		if(filter_var($newTeamId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("team id $newTeamId is not numeric"));
		}
		$newTeamId = intval($newTeamId);
		if($newTeamId <= 0) {
			throw(new RangeException("team Id $newTeamId is not positive"));
		}
		$this->teamId = $newTeamId;
	}

	public function insert(&$mysqli)
	{

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->teamId !== null) {
			throw(new mysqli_sql_exception("not a new team member"));
		}
		$query = "INSERT INTO team(teamName, teamCause) VALUES(?, ?)";
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

	public function delete(&$mysqli)
	{

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		if($this->teamId === null) {
			throw(new mysqli_sql_exception("Unable to delete a user that does not exist"));
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

	public function update($mysqli)
	{
		//* query//
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
/**
 * Created by PhpStorm.
 * User: Cass
 * Date: 11/7/2014
 * Time: 11:02 AM
 */
