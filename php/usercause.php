<?php
/**
 * To create a class that will create a relationship between the User and Cause tables
 * Connects the profile and the cause tables
 * @author Casimiro Vigil <cfvigil26@gmail.com>
 **/

require_once("profile.php");
require_once("cause.php");

class UserCause{
	/**
	 * The profileId that will link the profile to the causes
	 **/
	private $profileId;
	/**
	 * The causeId that will link the cause to the profiles
	 **/
	private $causeId;

	/**
	 * Creates the profile cause object
	 *
	 * @param mixed $newProfileId
	 * @param mixed $newCauseId
	 */

	public function __construct($newProfileId, $newCauseId){
		try{
			$this->setProfileId($newProfileId);

			$this->setCauseId($newCauseId);

		} catch(UnexpectedValueException $unexpectedValue) {

			throw(new UnexpectedValueException("Could not construct object ProfileCause", 0, $unexpectedValue));
		} catch(RangeException $range) {
			throw(new RangeException("Could not construct object ProfileCause", 0, $range));
		}

	}

	/**
	 * Creates an array with profileId and causeId to check for name.
	 *
	**/

	public function __get($name) {
		$data = array("profileId" => $this->profileId,
						  "causeId" => $this->causeId);
		if(array_key_exists($name, $data)) {
			return $data[$name];
		} else {
			var_dump($this->profileId);
			throw(new InvalidArgumentException("Unable to get $name"));
		}
	}

	/**
	 * Sets the value of profileId from profile class
	 *
	 * @param mixed $newProfileId profile id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if cause id is not positive
	 **/
	public function setProfileId($newProfileId)
	{

		//insure profile id is an integer
		if(filter_var($newProfileId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("profileId $newProfileId is not numeric"));
		}
		//insure profile id is a positive integer
		$newProfileId = intval($newProfileId);
		if($newProfileId <= 0) {
			throw(new RangeException("profileId $newProfileId is not positive."));
		}
		//take profile id out os quarantine
		$this->profileId = $newProfileId;
	}

	/**
	 * Sets the value of causeId from cause class
	 *
	 * @param mixed $newCauseId cause id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if Cause id is not positive
	 **/
	//set cause id to null if new object
	public function setCauseId($newCauseId){

		//insure cause id is an integer
		if(filter_var($newCauseId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("causeId $newCauseId is not numeric"));
		}

		//insure cause id is a positive integer
		$newCauseId = intval($newCauseId);
		if($newCauseId <= 0) {
			throw(new RangeException("causeId $newCauseId is not positive."));
		}
		//take cause id out os quarantine
		$this->causeId = $newCauseId;
	}

	/**
	 * Inserts the values into mySQL
	 *
	 * @param profileCause $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 **/
	public function insert(&$mysqli){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw(new mysqli_sql_exception("This is not a valid mysqli object"));
		}

		//enforce the profile id is null
		if($this->profileId === null){
			throw(new mysqli_sql_exception("Not a new ProfileCause Relationship"));
		}

		//enforce the cause id is null
		if($this->causeId === null){
			throw(new mysqli_sql_exception("Not a new ProfileCause relationship"));
		}

		//create a query template
		$query = "INSERT INTO userCause(profileId, causeId) VALUES (?, ?)";
		$statement = $mysqli->prepare($query);

		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
		//bind the variable to the place holder in the template
		$wasClean = $statement->bind_param("ii", $this->profileId, $this->causeId);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		//execute the statement
		if($statement->execute() === false){
			throw(new mysqli_sql_exception("Unable to execute statement"));
		}
	}

	/**
	 * Deletes the objects from mySQL
	 *
	 * @param ProfileCause $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function delete($mysqli){
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}
		//enforce the cause id and profile id are not null
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("Unable to delete an Cause that does not exist"));
		}

		if($this->causeId === null){
			throw(new mysqli_sql_exception("Unable to delete a Profile that does not exist"));
		}
		//create a query template
		$query		="DELETE FROM userCause WHERE profileId = ? AND causeId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the placeholder in the template
		$wasClean = $statement->bind_param("ii", $this->profileId, $this->causeId);
		if($wasClean === false){
			throw (new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * Updates the values within mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public function update($mysqli){

		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		//enforce the cause id and profile id are set to null
		if($this->profileId === null){
			throw (new mysqli_sql_exception("Cannot update cause that does not exist"));
		}
		if($this->causeId === null){
			throw (new mysqli_sql_exception("Cannot update profile that does not exist"));
		}

		//create a query template
		$query		="UPDATE userCause SET profileId = ?, causeId = ?";
		$statement  =$mysqli->prepare->$query;
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		//bind the member variables to the places holder in the template
		$wasClean = $statement->bind_param("ii", $this->profileId, $this->causeId);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute === false){
			throw(new mysqli_sql_exception("Unable to execute mySQL statement."));
		}
	}

	/**
	 * gets the mysqli object using the ProfileId, creating it if necessary
	 *
	 * @param $mysqli mysqli object
	 * @param mixed $profileId
	 * @return array|null
	 * @throw mysqli_sql_exception if unable to properly execute statement
	 */

	public static function getUserCauseByProfileId($mysqli,$profileId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the profileId before searching
		$profileId = trim($profileId);
		$profileId = filter_var($profileId, FILTER_VALIDATE_INT);

		// create query template
		$query = "SELECT profileId, causeId FROM userCause WHERE profileId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the profileId to the place holder in the template
		$wasClean = $statement->bind_param("i", $profileId);
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
		$profileIdSearch = array();

		// loop through the array and display the results
		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$profile = new userCause($row["profileId"], $row["causeId"]);
				$profileIdSearch [] = $profile;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to profileCause", 0, $exception));
			}
		}

		// return
		if($result->num_rows === 0) {
			return(null);
		} else {
			return($profileIdSearch);
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

	public static function getUserCauseByCauseId($mysqli,$causeId){
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the profile id before searching
		$causeId = trim($causeId);
		$causeId = filter_var($causeId, FILTER_VALIDATE_INT);

		// create query template
		$query = "SELECT profileId, causeId FROM userCause WHERE causeId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the profile id to the place holder in the template
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
				$cause = new userCause($row["profileId"], $row["causeId"]);
				$causeIdSearch [] = $cause;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to profileCause", 0, $exception));
			}
		}
		// return
		if($result->num_rows === 0) {
			return(null);
		} else {
			return($causeIdSearch);
		}
	}

	/**
	 * gets the Commentteam by teamId and commentId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $teamId and $commentId  profileId and teamId to search for
	 * @return int team and comment found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public static function getUserCauseByUserCauseId(&$mysqli, $profileId, $causeId) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the profileId and commentId before searching
		$profileId = filter_var($profileId, FILTER_VALIDATE_INT);
		if($profileId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		$causeId = filter_var($causeId, FILTER_VALIDATE_INT);
		if($causeId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT profileId, causeId FROM userCause WHERE profileId = ? AND causeId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the profileId and teamId to the place holder in the template
		$wasClean = $statement->bind_param("ii", $profileId, $causeId);
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
				$causeUser = new userCause($row["profileId"], $row["causeId"]);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));

			}
			//if we get a profileId and commentId I'm lucky and show it
			return ($causeUser);
		} else {

			return (null);
		}
	}
}
