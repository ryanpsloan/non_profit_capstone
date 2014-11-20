<?php
/**
 * creates the connections between comments and teams
 * Relationship between the teams and their comments
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

require_once("../php/team.php");
require_once("../php/comment.php");

class CommentTeam {
		/**
		 * Primary key that links the team to this table
		 */
		private $teamId;
		/**
		 * Primary key that link the comments to this table
		 */
		private $commentId;

	public function __construct($newTeamId, $newCommentId){
		try{
			$this->setTeamId($newTeamId);
			$this->setCommentId($newCommentId);
		} catch (UnexpectedValueException $unexpectedValue){
			throw(new UnexpectedValueException("Unable to construct commentTeam object", 0, $unexpectedValue));
		} catch (RangeException $rangeException){
			throw(new RangeException("Unable to construct commentTeam object", 0, $rangeException));
		}
	}

	public function __get($name) {
		$data = array("teamId" => $this->teamId,
						  "commentId" => $this->commentId);
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
	 * assigns the value for commentId
	 *
	 * @param mixed $newCommentId comment id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if comment id is not positive
	 **/
	public function setCommentId($newCommentId){
		if($newCommentId === null){
			throw(new UnexpectedValueException("commentId cannot be null."));
		}

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
	 * @param CommentTeam $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function insert(&$mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw(new mysqli_sql_exception("This is not a valid mysqli object"));
		}

		if($this->teamId === null){
			throw(new mysqli_sql_exception("This team does not exist"));
		}

		if($this->commentId === null){
			throw(new mysqli_sql_exception("This comment does not exist"));
		}

		$query = "INSERT INTO commentTeam(teamId, commentId)
		VALUES (?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->teamId, $this->commentId);

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
	 * @param CommentTeam $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 **/
	public function delete($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->teamId === null) {
			throw(new mysqli_sql_exception("Unable to delete a team that does not exist"));
		}

		if($this->commentId === null) {
			throw(new mysqli_sql_exception("Unable to delete a comment that does not exist"));
		}

		$query		="DELETE FROM commentTeam WHERE teamId = ? AND commentId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->teamId, $this->commentId);
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

		if($this->teamId === null){
			throw (new mysqli_sql_exception("cannot update team object that does not exist"));
		}

		if($this->commentId === null){
			throw (new mysqli_sql_exception("Cannot update comment object that does not exist"));
		}

		$query		="UPDATE commentTeam SET teamId = ?, commentId = ?";
		$statement  =$mysqli->prepare->$query;
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("ii", $this->teamId, $this->commentId);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute === false){
			throw(new mysqli_sql_exception("Unable to execute mySQL statement."));
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
	public static function getCommentTeamByTeamId($mysqli, $teamId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the teamId before searching
		$teamId = trim($teamId);
		$teamId = filter_var($teamId, FILTER_VALIDATE_INT);



		// create query template
		$query = "SELECT teamId, commentId FROM commentTeam WHERE teamId = ?";

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
				$commentTeam = new CommentTeam($row["teamId"], $row["commentId"]);
				$teamIdSearch [] = $commentTeam;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to team", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($teamIdSearch);
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
	public static function getCommentTeamByCommentId($mysqli, $commentId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the teamId before searching
		$commentId = trim($commentId);
		$commentId = filter_var($commentId, FILTER_VALIDATE_INT);



		// create query template
		$query = "SELECT teamId, commentId FROM commentTeam WHERE commentId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the teamId to the place holder in the template
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
				$commentTeam = new CommentTeam($row["teamId"], $row["commentId"]);
				$commentIdSearch [] = $commentTeam;
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
	 * gets the CommentUser by profileId and commentId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $profileId and $commentId  profileId and teamId to search for
	 * @return int profile and comment found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public static function getCommentTeamByTeamCommentId(&$mysqli, $teamId, $commentId) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the teamId and commentId before searching
		$teamId = filter_var($teamId, FILTER_VALIDATE_INT);
		if($teamId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		$commentId = filter_var($commentId, FILTER_VALIDATE_INT);
		if($commentId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT teamId, commentId FROM commentTeam WHERE teamId = ? AND commentId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the teamId and commentId to the place holder in the template
		$wasClean = $statement->bind_param("ii", $teamId, $commentId);
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

		//covert the associative array to a userId
		if($row !== null) {
			try {
				$commentTeam = new commentTeam($row["teamId"], $row["commentId"]);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to team", 0, $exception));

			}
			//if we get a profileId and commentId I'm lucky and show it
			return ($commentTeam);
		} else {
			//404 User not found
			return (null);
		}
	}

}