<?php
/**
 * This class will create the relationship between the team and the cause tables
 * Connects the team and the cause tables
 *
 * Author: Dameon Smith
 */

require_once("../php/team.php");
require_once("../php/cause.php");

class TeamCause{
	// The team id that links the team to the causes
	private $teamId;
	//The cause id that links the causes to the teams
	private $causeId;

	public function __construct($newTeamId, $newCauseId){
		try{
			$this->teamId = $newTeamId;

			$this->causeId = $newCauseId;
		} catch(UnexpectedValueException $unexpectedValue) {
				 throw(new UnexpectedValueException("Could not construct object TeamCause", 0, $unexpectedValue));
			 } catch(RangeException $range) {
			throw(new RangeException("Could not construct object TeamCause", 0, $range));
		}

	}

	// Need to insert the get or call function here.

	/**
	 * @param mixed $newTeamId team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if Cause id is not positive
	 **/
	public function setTeamId($newTeamId)
	{
		if($this->teamId === null) {
			$this->teamId = null;
			return;
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
	 * @param mixed $newCauseId team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if Cause id is not positive
	 **/
	public function setCauseId($newCauseId){
		if($this->teamId === null) {
			$this->teamId = null;
			return;
		}

		if(filter_var($newCauseId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("teamId $newCauseId is not numeric"));
		}

		$newCauseId = intval($newCauseId);
		if($newCauseId <= 0) {
			throw(new RangeException("teamId $newCauseId is not positive."));
		}

		$this->causeId = $newCauseId;
	}

	/**
	 * @param TeamCause $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function insert(&$mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw(new mysqli_sql_exception("This is not a valid mysqli object"));
		}

		if($this->teamId !== null){
			throw(new mysqli_sql_exception("Not a new TeamCause Relationship"));
		}

		if($this->causeId !== null){
			throw(new mysqli_sql_exception("Not a new TeamCause relationship"));
		}


		$query = "INSERT INTO teamCause(teamId, causeId) VALUES (?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->teamId, $this->causeId);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false){
			throw(new mysqli_sql_exception("Unable to execute statement"));
		}

		$this->causeId = $mysqli->insert_id;
		$this->teamId = $mysqli->insert_id;
	}

	/**
	 * @param TeamCause $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function delete($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->causeId === null) {
			throw(new mysqli_sql_exception("Unable to delete an Cause that does not exist"));
		}

		$query		="DELETE FROM teamCause WHERE causeId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("i", $this->causeId);
		if($wasClean === false){
			throw (new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
		
	}

	/**
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public function update($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->causeId === null){
			throw (new mysqli_sql_exception("Cannot update object that does not exist"));
		}

		$query		="UPDATE teamCause SET teamId = ?, causeId = ?";
		$statement  =$mysqli->prepare->$query;
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->causeId, $this->teamId);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute === false){
			throw(new mysqli_sql_exception("Unable to execute mySQL statement."));
		}
	}
	
	
}