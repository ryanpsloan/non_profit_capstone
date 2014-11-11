<?php
/**
 * mySQL Enabled User
 *
 * This is a mySQL enabled container for User authentication at a nonprofit site.
 *
 *
 * User: Martin
 */
//setup User Class and respective fields
class User {

	 //userId for the User; this is the primary key
	private $userId;

	//userName for the User; this is a unique field
	private $userName;

	//email for the User; this is a unique field
	private $email;

	//Hash of the password
	private $passwordHash;

	//salt used for hash
	private $salt;

	//authentication token used in new passwords
	private $authToken;

	//permissions for the user; this is a index field
	private $permissions;

	/** constructor for the User
	 *
	 * @param mixed $newUserId for userId(or null if new object)
	 * @param string $newUserName  for userName
	 * @param string $newEmail for email
	 * @param string $newPasswordHash PBKDF2 hash of the password
	 * @param string $newSalt salt used in the PBKDF2 hash
	 * @param mixed $newAuthToken  authentication token used in new accounts and password resets (or null if active User)
	 * @param int $newPermissions for user permission
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/

	public function __construct($newUserId, $newUserName, $newEmail, $newPasswordHash, $newSalt,
										 $newAuthToken, $newPermissions){
		try {
			$this->setUserId($newUserId);
			$this->setUserName($newUserName);
			$this->setEmail($newEmail);
			$this->setPasswordHash($newPasswordHash);
			$this->setSalt($newSalt);
			$this->setAuthToken($newAuthToken);
			$this->setPermissions($newPermissions);

			// catch exceptions and rethrow to caller
		} catch(UnexpectedValueException $unexpectedValue) {
			throw(new UnexpectedValueException ("Unable to construct User", 0, $unexpectedValue));

		} catch(RangeException $range) {
			throw(new RangeException("Unable to construct User", 0, $range));
		}

	}
	/**
	 * gets the value of User Id
	 *
	 * @return mixed userId (or null if new object)
	 **/
	public function getUserId() {
		return($this->userId);
	}

	/**
	 * sets the value of user id
	 *
	 * @param string $newUserId user id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if user id isn't positive
	 **/
	public function setUserId($newUserId) {
		//set allow the user id to be null if a new object
		if($newUserId === null) {
			$this->userId = null;
			return;
		}
		//first, ensure the user id is an integer
		if(filter_var($newUserId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("User id $newUserId is not numeric"));
		}

		// second, convert the user id to an integer and enforce it's positive
		$newUserId = intval($newUserId);
		if($newUserId <= 0) {
			throw(new RangeException ("User Id $newUserId is not positive"));
		}
		//remove User Id from quarantine
		$this->userId = $newUserId;
	}

	/** get characters for the user name
	 *
	 * @ return string value for user name
	 *
	 **/
	public function getUserName () {
		return($this->userName);
	}

	/**
	 * sets the value of user Name
	 *
	 * @param string $newUserName for userName
	 * @throws UnexpectedValueException if not a string
	 * @throws RangeException if user name is longer than 32 characters
	 **/

	//verify userName is a string
	public function setUserName($newUserName) {
		$newUserName = trim($newUserName);
		if(filter_var($newUserName, FILTER_SANITIZE_STRING) === false) {
			throw(new UnexpectedValueException("User name $newUserName is not a string"));
		}
		//verify userName is less than 33 characters
		$newUserNameLength = strlen($newUserName);
		if($newUserNameLength > 33) {
			throw(new RangeException("User Name $newUserName is longer than 32 characters"));
		}
		//remove UserName from quarantine
		$this->userName = $newUserName;
	}
	/**
	 * gets the value of email
	 *
	 * @return string value of email
	 **/
	public function getEmail() {
		return($this->email);
	}

	/**
	 * sets the value of email
	 *
	 * @param string $newEmail email
	 * @throws UnexpectedValueException if the input doesn't appear to be an Email
	 **/
	public function setEmail($newEmail) {
		// sanitize the Email as a likely Email
		$newEmail = trim($newEmail);
		if(($newEmail = filter_var($newEmail, FILTER_SANITIZE_EMAIL)) == false) {
			throw(new UnexpectedValueException("email $newEmail does not appear to be an email address"));
		}

		//remove email  from quarantine
		$this->email = $newEmail;
	}

	/**
	 * gets the value of password Hash
	 *
	 * @return string value of password Hash
	 **/
	public function getPasswordHash() {
		return($this->passwordHash);
	}

	/**
	 * sets the value of passwordHash
	 *
	 * @param string $newPasswordHash SHA512 PBKDF2 hash of the password
	 * @throws RangeException when input isn't a valid SHA512 PBKDF2 hash
	 **/
	public function setPasswordHash($newPasswordHash) {
		// verify the password is 128 hex characters
		$newPasswordHash   = trim($newPasswordHash);
		$newPasswordHash  = strtolower($newPasswordHash);
		$filterOptions = array("options" => array("regexp" => "/^[\da-f]{128}$/"));
		if(filter_var($newPasswordHash, FILTER_VALIDATE_REGEXP, $filterOptions) === false) {
			throw(new RangeException("password is not a valid SHA512 PBKDF2 hash"));
		}

		// remove the password from quarantine
		$this->passwordHash = $newPasswordHash;
	}

	/**
	 * gets the value of salt
	 *
	 * @return string value of salt
	 **/
	public function getSalt() {
		return($this->salt);
	}

	/**
	 * sets the value of salt
	 *
	 * @param string $newSalt salt (64 hexadecimal bytes)
	 * @throws RangeException when input isn't 64 hexadecimal bytes
	 **/
	public function setSalt($newSalt) {
		// verify the salt is 64 hex characters
		$newSalt   = trim($newSalt);
		$newSalt   = strtolower($newSalt);
		$filterOptions = array("options" => array("regexp" => "/^[\da-f]{64}$/"));
		if(filter_var($newSalt, FILTER_VALIDATE_REGEXP, $filterOptions) === false) {
			throw(new RangeException("salt is not 64 hexadecimal bytes"));
		}

		// finally, take the salt out of quarantine
		$this->salt = $newSalt;
	}

	/**
	 * gets the value of authentication token
	 *
	 * @return mixed value of authentication token (or null if active User)
	 **/
	public function getAuthToken() {
		return($this->authToken);
	}

	/**
	 * sets the value of authentication token
	 *
	 * @param mixed $newAuthToken authentication token (32 hexadecimal bytes) (or null if active User)
	 * @throws RangeException when input isn't 32 hexadecimal bytes
	 **/
	public function setAuthToken($newAuthToken) {
		// zeroth, set allow the authentication token to be null if an active object
		if($newAuthToken === null) {
			$this->authToken = null;
			return;
		}

		// verify the authentication token is 32 hex characters
		$newAuthToken   = trim($newAuthToken);
		$newAuthToken   = strtolower($newAuthToken);
		$filterOptions = array("options" => array("regexp" => "/^[\da-f]{32}$/"));
		if(filter_var($newAuthToken, FILTER_VALIDATE_REGEXP, $filterOptions) === false) {
			throw(new RangeException("authentication token is not 32 hexadecimal bytes"));
		}

		// remove authentication token out of quarantine
		$this->authToken = $newAuthToken;
	}

	/**
	 * sets the value for permissions
	 *
	 * @param int $newPermissions permissions
	 * @throws UnexpectedValueException if not an integer
	 * @throws RangeException if permissions isn't positive
	 **/
	public function setPermissions($newPermissions) {

		//first, ensure the user id is an integer
		if(filter_var($newPermissions, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("Permissions $newPermissions is not numeric"));
		}

		// second, convert the permissions to an integer and enforce it's positive
		$newPermissions = intval($newPermissions);
		if($newPermissions <= 0) {
			throw(new RangeException ("Permissions $newPermissions is not positive"));
		}
		//remove permissions from quarantine
		$this->permissions = $newPermissions;
	}

	/**
	 * inserts this User to mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function insert(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is null (i.e., don't insert a user that already exists)
		if($this->userId !== null) {
			throw(new mysqli_sql_exception("not a new user"));
		}

		// create query template
		$query     = "INSERT INTO user(userName, email, passwordHash, salt, authToken, permissions) VALUES(?, ?, ?, ?, ?, ?)";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("sssssi", $this->userName, $this->email, $this->passwordHash, $this->salt,
																	$this->authToken, $this->permissions);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// update the null userId with what mySQL just gave us
		$this->userId = $mysqli->insert_id;
	}

	/**
	 * deletes this User from mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function delete(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is not null (i.e., don't delete a user that hasn't been inserted)
		if($this->userId === null) {
			throw(new mysqli_sql_exception("Unable to delete a user that does not exist"));
		}

		// create query template
		$query     = "DELETE FROM user WHERE userId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holder in the template
		$wasClean = $statement->bind_param("i", $this->userId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * updates this User in mySQL
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public function update(&$mysqli) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// enforce the userId is not null (i.e., don't update a user that hasn't been inserted)
		if($this->userId === null) {
			throw(new mysqli_sql_exception("Unable to update a user that does not exist"));
		}

		// create query template
		$query     = "UPDATE user SET username = ?, email = ?, passwordHash = ?, salt = ?, authToken = ?, permissions = ?
 							WHERE userId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the member variables to the place holders in the template
		$wasClean = $statement->bind_param("sssssii", $this->userName, $this->email, $this->passwordHash, $this->salt,
																	 $this->authToken, $this->permissions, $this->userId);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}
	}

	/**
	 * gets the User by Email
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $email email to search for
	 * @return mixed User found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserByEmail(&$mysqli, $email) {
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the Email before searching
		$email = trim($email);
		$email = filter_var($email, FILTER_SANITIZE_EMAIL);

		// create query template
		$query     = "SELECT userId, userName, email, passwordHash, salt, authToken, permissions FROM user WHERE email = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the email to the place holder in the template
		$wasClean = $statement->bind_param("s", $email);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query *pounds fists*
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// since this is a unique field, this will only return 0 or 1 results. So...
		// 1) if there's a result, we can make it into a User object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc();

		// convert the associative array to a User
		if($row !== null) {
			try {
				$user = new User($row["userId"], $row["userName"], $row["email"], $row["passwordHash"], $row["salt"],
											  $row["authToken"], $row["permissions"]);
			}
			catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to User", 0, $exception));
			}

			// if we got here, the User is good - return it
			return($user);
		} else {
			// 404 User not found - return null instead
			return(null);
		}
	}
	/**
	 * gets the User by userName
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param string $userName userName to search for
	 * @return mixed User found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserByUserName(&$mysqli, $userName)
	{
		// handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}

		// sanitize the userName before searching
		$userName = trim($userName);
		$userName = filter_var($userName, FILTER_SANITIZE_STRING);

		// create query template
		$query = "SELECT userId, userName, email, passwordHash, salt, authToken, permissions FROM user WHERE userName = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception("Unable to prepare statement"));
		}

		// bind the email to the place holder in the template
		$wasClean = $statement->bind_param("s", $userName);
		if($wasClean === false) {
			throw(new mysqli_sql_exception("Unable to bind parameters"));
		}

		// execute the statement
		if($statement->execute() === false) {
			throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		}

		// get result from the SELECT query *pounds fists*
		$result = $statement->get_result();
		if($result === false) {
			throw(new mysqli_sql_exception("Unable to get result set"));
		}

		// since this is a unique field, this will only return 0 or 1 results. So...
		// 1) if there's a result, we can make it into a User object normally
		// 2) if there's no result, we can just return null
		$row = $result->fetch_assoc();

		// convert the associative array to a User
		if($row !== null) {
			try {
				$user = new User($row["userId"], $row["userName"], $row["email"], $row["passwordHash"], $row["salt"],
					$row["authToken"], $row["permissions"]);
			} catch(Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new mysqli_sql_exception("Unable to convert row to User", 0, $exception));
			}

			// if we got here, the User is good - return it
			return ($user);
		} else {
			// 404 User not found - return null instead
			return (null);
		}
	}
	/**
	 * gets the User by userId
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $userId userId to search for
	 * @return mixed User found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/

	public static function getUserByUserId(&$mysqli, $userId)
	{
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the userId before searching
		$userId = filter_var($userId, FILTER_VALIDATE_INT);
		if($userId === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT userId, userName, email, passwordHash, salt, authToken, permissions FROM user WHERE userId = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the user Id to the place holder in the template
		$wasClean = $statement->bind_param("i", $userId);
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
				$user = new user($row["userId"], $row["userName"], $row["email"], $row["passwordHash"], $row["salt"],
					$row["authToken"], $row["permissions"]);
			} catch(Exception $exception) {
				//rethrow
				throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));

			}
			//if we get a userId I'm lucky and show it
			return ($user);
		} else {
			//404 User not found
			return (null);
		}
	}

	/**
	 * gets the User by permissions
	 *
	 * @param resource $mysqli pointer to mySQL connection, by reference
	 * @param int $permission permissions to search for
	 * @return mixed User found or null if not found
	 * @throws mysqli_sql_exception when mySQL related errors occur
	 **/
	public static function getUserByPermissions(&$mysqli, $permissions)
	{
		//handle degenerate cases
		if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			throw(new mysqli_sql_exception("input is not a mysqli object"));
		}
		//sanitize the UserId before searching
		$permissions = trim($permissions);
		$permissions = filter_var($permissions, FILTER_VALIDATE_INT);
		$permissions = intval($permissions);
		if($permissions === null) {
			throw(new mysqli_sql_exception("input is null"));
		}

		//Create query template
		$query = "SELECT userId, userName, email, passwordHash, salt, authToken, permissions FROM user WHERE permissions = ?";
		$statement = $mysqli->prepare($query);
		if($statement === false) {
			throw(new mysqli_sql_exception ("unable to prepare statement"));
		}

		//bind the user Id to the place holder in the template
		$wasClean = $statement->bind_param("i", $permissions);
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

		//many users can have different permissions.
		//if there's a result, we can show it
		//if not error code 404

		//userArrayCounter = 0
	$userArray = array();
	while(($row = $result->fetch_assoc()) !== null) {

		//covert the associative array to a userId and repeat for all permissions
		try {
			$user = new user($row["userId"], $row["userName"], $row["email"], $row["passwordHash"], $row["salt"],
				$row["authToken"], $row["permissions"]);
			//build empty array for sql to fill
			$userArray [] = $user;

		} catch(Exception $exception) {
			//rethrow
			throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));
		}
	}
			//if we get a userId I'm lucky and show it
		if ($result->num_rows ===0) {
				return (null);
		} else {

			return ($userArray);
		}
	}
}