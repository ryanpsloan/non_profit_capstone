<?php

/**
 * mySQL Enabled Intersection Table for commentUser
 *
 * This is a mySQL enabled container for comment user interaction at a nonprofit site.
 *
 *
 * User: Martin
 */
//setup CommentUser Class and respective fields
class CommentUser {

	//profileId for the CommentUser intersection table; this is the primary key and foreign key.
	private $profileId;

	//commentId for the CommentUser intersection table; this is the primary key and foreign key.
	private $commentId;


	/** constructor for the CommentUser
	 *
	 * @param int $newProfileId for profileId
	 * @param int $newCommentId  for commentId
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/

	public function __construct($newProfileId, $newCommentId) {
		try {
			$this->setProfileId($newProfileId);
			$this->setCommentId($newCommentId);

			// catch exceptions and rethrow to caller
		} catch(UnexpectedValueException $unexpectedValue) {
			throw(new UnexpectedValueException ("Unable to construct Profile", 0, $unexpectedValue));

		} catch(RangeException $range) {
			$range->getMessage();
			throw(new RangeException("Unable to construct Profile", 0, $range));
		}
	}

	/**
	 * gets the value of Profile Id
	 *
	 * @return int profileId
	 **/
	public function getProfileId() {
		return($this->profileId);
	}

	/**
	 * sets the value of profile id
	 *
	 * @param string $newProfileId profile id
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if profile id isn't positive
	 **/
	public function setProfileId($newProfileId) {

		//first, ensure the profile id is an integer
		if(filter_var($newProfileId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Profile id $newProfileId is not numeric"));
		}

		// second, convert the profile id to an integer and enforce it's positive
		$newProfileId = intval($newProfileId);
		if($newProfileId <= 0) {
			throw(new RangeException ("Profile Id $newProfileId is not positive"));
		}
		//remove profile Id from quarantine
		$this->profileId = $newProfileId;
	}

	/**
	 * gets the value of comment Id
	 *
	 * @return int commentId
	 **/
	public function getCommentId() {
		return($this->commentId);
	}

	/**
	 * sets the value of comment id
	 *
	 * @param string $newCommentId comment id
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if comment id isn't positive
	 **/
	public function setCommentId($newCommentId) {

		//first, ensure the Comment Id is an integer
		if(filter_var($newCommentId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Comment Id $newCommentId is not numeric"));
		}

		// second, convert the comment id to an integer and enforce it's positive
		$newCommentId = intval($newCommentId);
		if($newCommentId <= 0) {
			throw(new RangeException ("Comment Id $newCommentId is not positive"));
		}
		//remove commentId from quarantine
		$this->commentId = $newCommentId;
	}

	/**
	 * inserts this profile and comment to mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the profileId is null (i.e., don't insert a user that already exists)
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("not a new user"));
		}

		// enforce the commentId is null (i.e., don't insert a user that already exists)
		if($this->commentId === null) {
			throw(new mysqli_sql_exception("not a new user"));
		}

		// create query template
		$query     = "INSERT INTO commentUser(profileId, commentId) VALUES(?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("ii", $this->profileId, $this->commentId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

	}
	/**
	 * deletes this profile and comment from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the profileId and commentId is not null (i.e., don't delete a user that hasn't been inserted)
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("Unable to delete a user that does not exist"));
		}

		if($this->commentId === null) {
			throw(new mysqli_sql_exception("Unable to delete a team that does not exist"));
		}
		// create query template
		$query     = "DELETE FROM commentUser WHERE profileId = ? AND commentId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("ii", $this->profileId, $this->commentId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates this profile and comment in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the profileId and commentId is not null (i.e., don't update a user that hasn't been inserted)
		if($this->profileId === null) {
			throw(new mysqli_sql_exception("Unable to update a user that does not exist"));
		}

		if($this->commentId === null) {
			throw(new mysqli_sql_exception("Unable to update a user that does not exist"));
		}

		// create query template
		$query     = "UPDATE commentUser SET profileId = ?, commentId = ?	WHERE profileId = ? AND commentId = ?";
		$statement = $mysqli->prepare($query);
		echo "<p>before execution</p>";
		var_dump($this);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		//fixme because dameon said so
		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("iiii", $this->profileId, $this->commentId, $this->profileId, $this->commentId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}
		echo "<p>after execution</p>";
		var_dump($this);
		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
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

	public static function getCommentUserByProfileCommentId(&$mysqli, $profileId, $commentId) {

		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the profileId and commentId before searching
		$profileId = filter_var($profileId, FILTER_VALIDATE_INT);
		if($profileId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		$commentId = filter_var($commentId, FILTER_VALIDATE_INT);
		if($commentId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT profileId, commentId FROM commentUser WHERE profileId = ? AND commentId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the profileId and teamId to the place holder in the template
		$wasClean = $statement->bind_param("ii", $profileId, $commentId);
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
				$commentUser = new commentUser($row["profileId"], $row["commentId"]);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));

			}
			//if we get a profileId and commentId I'm lucky and show it
			return ($commentUser);
		} else {
			//404 User not found
			return (null);
		}
	}

}