<?php
/**
 * This class will create the relationship between the team and the cause tables
 * Connects the team and the cause tables
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 **/

require_once("../php/team.php");
require_once("../php/cause.php");

class TeamCause{
	/**
	* The team id that links the team to the causes
	**/
	private $teamId;
	/**
	* The cause id that links the causes to the teams
	**/
	private $causeId;

	/**
	 * Creates the team cause object
	 *
	 * @param mixed $newTeamId
	 * @param mixed $newCauseId
	 */

	public function __construct($newTeamId, $newCauseId){
		try{
			$this->setTeamId($newTeamId);

			$this->setCauseId($newCauseId);
		} catch(UnexpectedValueException $unexpectedValue) {
				 throw(new UnexpectedValueException("Could not construct object TeamCause", 0, $unexpectedValue));
			 } catch(RangeException $range) {
			throw(new RangeException("Could not construct object TeamCause", 0, $range));
		}

	}

	public function __get($name) {
		$data = array("teamId" => $this->teamId,
						  "causeId" => $this->causeId);
		if(array_key_exists($name, $data)) {
			return $data[$name];
		} else {
			throw(new InvalidArgumentException("Unable to get $name"));
		}
	}

	/**
	 * Sets the value of teamId from team class
	 *
	 * @param mixed $newTeamId team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if Cause id is not positive
	 **/
	public function setTeamId($newTeamId)
	{
		if($newTeamId === null) {
			throw (new UnexpectedValueException("teamId cannot be null"));
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
	 * Sets the value of causeId from cause class
	 *
	 * @param mixed $newCauseId cause id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if Cause id is not positive
	 **/
	public function setCauseId($newCauseId){
		if($newCauseId === null) {
			throw (new UnexpectedValueException("causeId does not exist"));
		}

		if(filter_var($newCauseId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("causeId $newCauseId is not numeric"));
		}

		$newCauseId = intval($newCauseId);
		if($newCauseId <= 0) {
			throw(new RangeException("causeId $newCauseId is not positive."));
		}

		$this->causeId = $newCauseId;
	}

	/**
	 * Inserts the values into mySQL
	 *
	 * @param TeamCause $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 **/
	public function insert(&$mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw(new mysqli_sql_exception("This is not a valid mysqli object"));
		}

		/*if($this->teamId !== null){
			throw(new mysqli_sql_exception("Not a new TeamCause Relationship"));
		}

		if($this->causeId !== null){
			throw(new mysqli_sql_exception("Not a new TeamCause relationship"));
		}*/


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

		if($this->causeId === null) {
			throw(new mysqli_sql_exception("Unable to delete an Cause that does not exist"));
		}

		if($this->teamId === null){
			throw(new mysqli_sql_exception("Unable to delete a Team that does not exist"));
		}

		$query		="DELETE FROM teamCause WHERE causeId = ? AND teamId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->causeId, $this->teamId);
		if($wasClean === false){
			throw (new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
		
	}

	/**
	 * gets the mysqli object using the TeamId, creating it if necessary
	 *
	 * @param $mysqli mysqli object
	 * @param mixed $teamId
	 * @return array|null
	 * @throw mysqli_sql_exception if unable to properly execute statement
	 */
	
	public static function getTeamCauseByTeamId($mysqli,$teamId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the teamId before searching
		$teamId = trim($teamId);
		$teamId = filter_var($teamId, FILTER_VALIDATE_INT);

		// create query template
		$query = "SELECT teamId, causeId FROM teamCause WHERE teamId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the teamId to the place holder in the template
		$wasClean = $statement->bind_param("i", $teamId);
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
		$teamIdSearch = array();

		// Loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$team = new TeamCause($row["teamId"], $row["causeId"]);
				$teamIdSearch [] = $team;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to teamCause", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($teamIdSearch);
		}
	}

	/**
	 * gets the mysqli object using the causeId, creating it if necessary
	 *
	 * @param $mysqli mysqli object
	 * @param mixed $causeId
	 * @return array|null
	 * @throw mysqli_sql_exception if unable to execute method
	 */

	public static function getTeamCauseByCauseId($mysqli,$causeId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the teamId before searching
		$causeId = trim($causeId);
		$causeId = filter_var($causeId, FILTER_VALIDATE_INT);

		// create query template
		$query = "SELECT teamId, causeId FROM teamCause WHERE causeId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the teamId to the place holder in the template
		$wasClean = $statement->bind_param("i", $causeId);
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
		$causeIdSearch = array();

		// Loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$cause = new TeamCause($row["teamId"], $row["causeId"]);
				$causeIdSearch [] = $cause;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to teamCause", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($causeIdSearch);
		}
	}
}