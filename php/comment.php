<?php
/**
 * Serves as the table that stores comments, and their related information
 * Stores the comments
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

class Comment{
	/**
	 * The Id to identify each comment, serves as the primary key
	 */
	private $commentId;
	/**
	 * The text of the actual comment
	 */
	private $commentText;
	/**
	 * The date and time in which the comment was made
	 */
	private $commentDate;

	public function __construct($newCommentId, $newCommentText, $newCommentDate){
		try{
			$this->setCommentId($newCommentId);

			$this->setCommentText($newCommentText);

			$this->setCommentDate($newCommentDate);
		} catch (UnexpectedValueException $unexpectedValue){
			throw(new UnexpectedValueException("Unable to construct Comment object", 0, $unexpectedValue));
		} catch (RangeException $rangeException){
			throw(new RangeException("Unable to construct Comment object", 0, $rangeException));
		}
	}

	public function __get($name)
	{
		$data = array("commentId"        => $this->commentId,
						  "commentText"       => $this->commentText,
						  "commentDate"    => $this->commentDate);
		if(array_key_exists($name, $data)) {
			return $data[$name];
		} else {
			throw(new InvalidArgumentException("Unable to get $name"));
		}
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
			$this->commentId = null;
			return;
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
	 * sets the data for commentText
	 * 
	 * @param string $newCommentText commentText
	 */
	public function setCommentText($newCommentText){
		$newCommentText = trim($newCommentText);
		$newCommentText = filter_var($newCommentText, FILTER_SANITIZE_STRING);
		// todo add exception for comments that are too long

		$this->commentText = $newCommentText;
	}

	/**
	 * sets the value of date created
	 *
	 * @param mixed $newCommentDate object or string with the date created
	 * @throws RangeException if date is not a valid date
	 **/
	public function setCommentDate($newCommentDate) {
		// zeroth, allow a DateTime object to be directly assigned
		if(gettype($newCommentDate) === "object" && get_class($newCommentDate) === "DateTime") {
			$this->commentDate = $newCommentDate;
			return;
		}

		// treat the date as a mySQL date string
		$newCommentDate = trim($newCommentDate);
		if((preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $newCommentDate, $matches)) !== 1) {
			throw(new RangeException("$newCommentDate is not a valid date"));
		}

		// verify the date is really a valid calendar date
		$year  = intval($matches[1]);
		$month = intval($matches[2]);
		$day   = intval($matches[3]);
		if(checkdate($month, $day, $year) === false) {
			throw(new RangeException("$newCommentDate is not a Gregorian date"));
		}

		// finally, take the date out of quarantine
		$newCommentDate = DateTime::createFromFormat("Y-m-d H:i:s", $newCommentDate);
		$this->commentDate = $newCommentDate;
	}

	/**
	 * Inserts the comment object into mysql
	 *
	 * @param comment $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occurs
	 */
	public function insert(&$mysqli)	{
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->commentId !== null) {
			throw(new mysqli_sql_exception("Not a new comment"));
		}

		$query = "INSERT INTO comment(commentText, commentDate) VALUES(?,?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$dateString = $this->commentDate->format("Y-m-d H:i:s");

		$wasClean = $statement->bind_param("ss", $this->commentText, $dateString);

		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw (new mysqli_sql_exception("Unable to execute mySQL insert statement"));
		}

		$this->commentId = $mysqli->insert_id;
	}

	/**
	 * deletes the comment object from mysql by grabbing the comment Id
	 *
	 *@param comment $mysqli pointer to mySQL connection by reference
	 * @throws mysqli_sql_exception when mySQL related error occur
	 */
	public function delete($mysqli){
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->commentId === null) {
			throw(new mysqli_sql_exception("Unable to delete an comment that does not exist"));
		}

		$query		="DELETE FROM comment WHERE commentId = ?";
		$statement  =$mysqli->prepare($query);
		if($statement === false){
			throw (new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("i", $this->commentId);
		if($wasClean === false){
			throw (new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates the objects values in mysql
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update($mysqli) {
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli"){
			throw (new mysqli_sql_exception("Input is not a mysqli object"));
		}

		if($this->commentId === null){
			throw (new mysqli_sql_exception("Cannot update object that does not exist"));
		}

		// Convert date to strings
		if($this->commentDate === null) {
			$dateCreated = null;
		} else {
			$commentDate = $this->commentDate->format("Y-d-m H:i:s");
		}

		$query		="UPDATE comment SET commentId = ?, commentText = ?, commentDate = ?";
		$statement  =$mysqli->prepare->$query;
		if($statement === false){
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("iss", $this->commentId, $this->commentText,
			$this->commentDate);

		if($wasClean === false){
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute === false){
			throw(new mysqli_sql_exception("Unable to execute mySQL statement."));
		}
	}

	/**
	 * gets the mysqli object using the commentId, creating it if necessary
	 *
	 * @param $mysqli
	 * @param mixed $commentId
	 * @return mysqli shared mysqli object
	 * @throws mysqli_sql_exception if the object cannot be created
	 **/	
	public static function getCommentByCommentId(&$mysqli, $commentId) {
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the CommentDate before searching
		$commentId = trim($commentId);
		$commentId = filter_var($commentId, FILTER_VALIDATE_INT);

		// create query template
		$query = "SELECT commentId, commentText, commentDate FROM comment WHERE commentId = ?";

		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}
		//bind the email to the place holder in the template
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


		$row = $result->fetch_assoc();

		// convert the associative array to a Comment
		if($row !== null) {
			try {
				$comment = new Comment($row["commentId"], $row["commentText"],
					$row["commentDate"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to Comment", 0, $exception));
			}

			// if we got here, the Comment is good - return it
			return($comment);
		} else {
			// 404 Comment not found - return null instead
			return(null);
		}
	}

	/**
	 * gets the mysqli object by finding the date, creating it if necessary
	 *
	 * @param $mysqli
	 * @param mixed $commentDate
	 * @return mysqli shared mysqli object
	 * @throws mysqli_sql_exception if the object cannot be created
	 **/
	public static function getCommentByCommentDate(&$mysqli, $commentDate) {

		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		$commentDate = trim($commentDate);
		$commentDate = filter_var($commentDate, FILTER_SANITIZE_STRING);

		$query = "SELECT commentId, commentTitle, commentDate, commentLocation FROM comment WHERE commentDate = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		$wasClean = $statement->bind_param("s", $commentDate);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set."));
		}

		$commentDateSearch = array();

		while(($row = $result->fetch_assoc()) !== null) {

			try {
				$comment = new Comment($row["commentId"], $row["commentDate"],
					$row["commentText"]);
				$commentDateSearch [] = $comment;
			} catch(Exception $exception) {

				throw(new mysqli_sql_exception("Unable to convert row to comment", 0, $exception));
			}
		}

		if($result->num_rows === 0) {
			return(null);
		} else {
			return($commentDateSearch);
		}
	}
}