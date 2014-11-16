  <?php
/**
 *mySQL Enabled Profile
 *
 * This is a mySQL enabled container for profile identification at a nonprofit site.
 *
 * User: Martin
 **/

//setup Profile Class and respective fields
  class Profile
  {

	  //profileId for the Profile; this is the primary key
	  private $profileId;

	  //userId for the Profile; this is the foreign key
	  private $userId;

	  //userTitle for the Profile
	  private $userTitle;

	  //Users first name for profile
	  private $firstName;

	  //Users middle initial for profile
	  private $midInit;

	  //Users last name for profile
	  private $lastName;

	  //Users biography (a little about themselves) for profile
	  private $bio;

	  //attention for postal regulation for profile
	  private $attention;

	  //street1 for profile mailing address
	  private $street1;

	  //street2 for profile mailing address in case street 1 was not enough
	  private $street2;

	  //city for profile of user
	  private $city;

	  //state for profile
	  private $state;

	  //zipCode for profile
	  private $zipCode;

	  /** constructor for the Profile
		*
		* @param mixed  $newProfileId for profileId(or null if new object)
		* @param int    $newUserId    for UserId
		* @param string $newUserTitle for userTitle
		* @param string $newFirstName for firstName
		* @param string $newMidInit   for middleInitial
		* @param string $newLastName  for lastName
		* @param string $newBio       for biography
		* @param string $newAttention for attention
		* @param string $newStreet1   for street1
		* @param string $newStreet2   for street2
		* @param string $newCity      for city
		* @param string $newState     for state
		* @param string $newZipCode   for Zip Code
		* @throws UnexpectedValueException when a parameter is of the wrong type
		* @throws RangeException when a parameter is invalid
		**/

	  public function __construct($newProfileId, $newUserId, $newUserTitle, $newFirstName, $newMidInit, $newLastName,
											$newBio, $newAttention, $newStreet1, $newStreet2, $newCity, $newState, $newZipCode)
	  {
		  try {
			  $this->setProfileId($newProfileId);
			  $this->setUserId($newUserId);
			  $this->setUserTitle($newUserTitle);
			  $this->setFirstName($newFirstName);
			  $this->setMidInit($newMidInit);
			  $this->setLastName($newLastName);
			  $this->setBio($newBio);
			  $this->setAttention($newAttention);
			  $this->setStreet1($newStreet1);
			  $this->setStreet2($newStreet2);
			  $this->setCity($newCity);
			  $this->setState($newState);
			  $this->setZipCode($newZipCode);

			  // catch exceptions and rethrow to caller
		  } catch(UnexpectedValueException $unexpectedValue) {
			  throw(new UnexpectedValueException ("Unable to construct Profile", 0, $unexpectedValue));

		  } catch(RangeException $range) {
			  throw(new RangeException("Unable to construct Profile", 0, $range));
		  }

	  }

	  /**
		* gets the value of profileId
		*
		* @return mixed profileId (or null if new object)
		**/
	  public function getProfileId()
	  {
		  return ($this->profileId);
	  }

	  /**
		* sets the value of profile id
		*
		* @param string $newProfileId profile id (or null if new object)
		* @throws UnexpectedValueException if not an integer or null
		* @throws RangeException if user id isn't positive
		**/
	  public function setProfileId($newProfileId)
	  {
		  //set allow the profile id to be null if a new object
		  if($newProfileId === null) {
			  $this->profileId = null;
			  return;
		  }
		  //first, ensure the profile id is an integer
		  if(filter_var($newProfileId, FILTER_VALIDATE_INT) === false) {
			  throw(new UnexpectedValueException("Profile id $newProfileId is not numeric"));
		  }

		  // second, convert the profile id to an integer and enforce it's positive
		  $newProfileId = intval($newProfileId);
		  if($newProfileId <= 0) {
			  throw(new RangeException ("Profile Id $newProfileId is not positive"));
		  }
		  //remove Profile Id from quarantine
		  $this->profileId = $newProfileId;
	  }

	  /** Gets the value of user Id
		*
		* @return  int value for user Id
		* @throws UnexpectedValueException if not an integer or null
		* @throws RangeException if user id isn't positive
		**/
	  public function getUserId()
	  {
		  return ($this->userId);
	  }

	  //verify that user id is an integer
	  public function setUserId($newUserId)
	  {
		  if(filter_var($newUserId, FILTER_VALIDATE_INT) === false) {
			  throw(new UnexpectedValueException("User id $newUserId is not numeric"));
		  }
		  //ensures user id is positive
		  $newUserId = intval($newUserId);
		  if($newUserId <= 0) {
			  throw(new RangeException ("contact id $newUserId is not positive"));
		  }
		  //remove Profile Id from quarantine
		  $this->userId = $newUserId;
	  }

	  /** get characters for the user title
		*
		* @ return string value for userTitle
		*
		**/
	  public function getUserTitle()
	  {
		  return ($this->userTitle);
	  }

	  /**
		* sets the value of user Title
		*
		* @param string $newUserTitle for userTitle
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if user name is longer than 64 characters
		**/

	  //verify userTitle is a string
	  public function setUserTitle($newUserTitle)
	  {
		  $newUserTitle = trim($newUserTitle);
		  if(filter_var($newUserTitle, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("User title $newUserTitle is not a string"));
		  }
		  //verify userTitle is less than 64 characters
		  $newUserTitleLength = strlen($newUserTitle);
		  if($newUserTitleLength > 65) {
			  throw(new RangeException("User title $newUserTitle is longer than 64 characters"));
		  }
		  //remove UserTitle from quarantine
		  $this->userTitle = $newUserTitle;
	  }

	  /** get characters for the firstName
		*
		* @ return string value for firstName
		*
		**/
	  public function getFirstName()
	  {
		  return ($this->firstName);
	  }

	  /**
		* sets the value of firstName
		*
		* @param string $newFirstName for firstName
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if firstName is longer than 32 characters
		**/

	  //verify firstName is a string
	  public function setFirstName($newFirstName)
	  {
		  $newFirstName = trim($newFirstName);
		  if(filter_var($newFirstName, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("First Name $newFirstName is not a string"));
		  }
		  //verify firstName is less than 32 characters
		  $newFirstNameLength = strlen($newFirstName);
		  if($newFirstNameLength > 33) {
			  throw(new RangeException("First Name $newFirstName is longer than 32 characters"));
		  }
		  //remove firstName from quarantine
		  $this->firstName = $newFirstName;
	  }

	  /** get characters for the midInit
		*
		* @ return string value for midInit
		*
		**/
	  public function getMidInit()
	  {
		  return ($this->midInit);
	  }

	  /**
		* sets the value of midInit
		*
		* @param string $newMidInit for midInit
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if midInit is longer than 2 characters
		**/

	  //verify midInit is a string
	  public function setMidInit($newMidInit)
	  {
		  $newMidInit = trim($newMidInit);
		  if(filter_var($newMidInit, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("Middle Initial $newMidInit is not a string"));
		  }
		  //verify midInit is less than 2 characters
		  $newMidInitLength = strlen($newMidInit);
		  if($newMidInitLength > 3) {
			  throw(new RangeException("Middle Initial $newMidInit is longer than 2 characters"));
		  }
		  //remove midInit from quarantine
		  $this->midInit = $newMidInit;
	  }

	  /** get characters for the lastName
		*
		* @ return string value for lastName
		*
		**/
	  public function getlastName()
	  {
		  return ($this->lastName);
	  }

	  /**
		* sets the value of lastName
		*
		* @param string $newLastName for lastName
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if lastName is longer than 32 characters
		**/

	  //verify lastName is a string
	  public function setLastName($newLastName)
	  {
		  $newLastName = trim($newLastName);
		  if(filter_var($newLastName, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("Last Name $newLastName is not a string"));
		  }
		  //verify lastName is less than 32 characters
		  $newLastNameLength = strlen($newLastName);
		  if($newLastNameLength > 33) {
			  throw(new RangeException("Last Name $newLastName is longer than 32 characters"));
		  }
		  //remove lastName from quarantine
		  $this->lastName = $newLastName;
	  }

	  /** get characters for the bio
		*
		* @ return string value for bio
		*
		**/
	  public function getBio()
	  {
		  return ($this->bio);
	  }

	  /**
		* sets the value of bio
		*
		* @param string $newBio for bio
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if bio is longer than 1000 characters
		**/

	  //verify bio is a string
	  public function setBio($newBio)
	  {
		  $newBio = trim($newBio);
		  if(filter_var($newBio, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("Biography $newBio is not a string"));
		  }
		  //verify bio is less than 1000 characters
		  $newBioLength = strlen($newBio);
		  if($newBioLength > 1001) {
			  throw(new RangeException("Biography $newBio is longer than 1000 characters"));
		  }
		  //remove bio from quarantine
		  $this->bio = $newBio;
	  }

	  /** get characters for the attention
		*
		* @ return string value for attention
		*
		**/
	  public function getAttention()
	  {
		  return ($this->attention);
	  }

	  /**
		* sets the value of attention
		*
		* @param string $newAttention for attention
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if attention is longer than 64 characters
		**/

	  //verify attention is a string
	  public function setAttention($newAttention)
	  {
		  $newAttention = trim($newAttention);
		  if(filter_var($newAttention, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("Attention $newAttention is not a string"));
		  }
		  //verify attention is less than 64 characters
		  $newAttentionLength = strlen($newAttention);
		  if($newAttentionLength > 65) {
			  throw(new RangeException("Attention $newAttention is longer than 64 characters"));
		  }
		  //remove attention from quarantine
		  $this->attention = $newAttention;
	  }

	  /** get characters for street1
		*
		* @ return string value for street1
		*
		**/
	  public function getStreet1()
	  {
		  return ($this->street1);
	  }

	  /**
		* sets the value of street1
		*
		* @param string $newStreet1 for street1
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if street1 is longer than 64 characters
		**/

	  //verify street1 is a string
	  public function setStreet1($newStreet1)
	  {
		  $newStreet1 = trim($newStreet1);
		  if(filter_var($newStreet1, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("Street $newStreet1 is not a string"));
		  }
		  //verify street1 is less than 64 characters
		  $newStreet1Length = strlen($newStreet1);
		  if($newStreet1Length > 65) {
			  throw(new RangeException("Street $newStreet1 is longer than 64 characters"));
		  }
		  //remove street1 from quarantine
		  $this->street1 = $newStreet1;
	  }

	  /** get characters for street2
		*
		* @ return string value for street2
		*
		**/
	  public function getStreet2()
	  {
		  return ($this->street2);
	  }

	  /**
		* sets the value of street2
		*
		* @param string $newStreet2 for street2
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if street2 is longer than 64 characters
		**/

	  //verify street2 is a string
	  public function setStreet2($newStreet2)
	  {
		  $newStreet2 = trim($newStreet2);
		  if(filter_var($newStreet2, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("Street $newStreet2 is not a string"));
		  }
		  //verify street2 is less than 64 characters
		  $newStreet2Length = strlen($newStreet2);
		  if($newStreet2Length > 65) {
			  throw(new RangeException("Street $newStreet2 is longer than 64 characters"));
		  }
		  //remove street2 from quarantine
		  $this->street2 = $newStreet2;
	  }

	  /** get characters for city
		*
		* @ return string value for city
		*
		**/
	  public function getCity()
	  {
		  return ($this->city);
	  }

	  /**
		* sets the value of city
		*
		* @param string $newCity for city
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if city is longer than 64 characters
		**/

	  //verify city is a string
	  public function setCity($newCity)
	  {
		  $newCity = trim($newCity);
		  if(filter_var($newCity, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("City $newCity is not a string"));
		  }
		  //verify city is less than 64 characters
		  $newCityLength = strlen($newCity);
		  if($newCityLength > 65) {
			  throw(new RangeException("City $newCity is longer than 64 characters"));
		  }
		  //remove city from quarantine
		  $this->city = $newCity;
	  }

	  /** get characters for state
		*
		* @ return string value for state
		*
		**/
	  public function getState()
	  {
		  return ($this->state);
	  }

	  /**
		* sets the value of state
		*
		* @param string $newState for midState
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if state is longer than 2 characters
		**/

	  //verify state is a string
	  public function setState($newState)
	  {
		  $newState = trim($newState);
		  if(filter_var($newState, FILTER_SANITIZE_STRING) === false) {
			  throw(new UnexpectedValueException("State $newState is not a string"));
		  }
		  //verify state is less than 2 characters
		  $newStateLength = strlen($newState);
		  if($newStateLength > 3) {
			  throw(new RangeException("State $newState is longer than 2 characters"));
		  }
		  //remove state from quarantine
		  $this->state = $newState;
	  }

	  /**
		* gets the value of ZipCode
		*
		* @return int value for zipCode
		**/
	  public function getZipCode()
	  {
		  return ($this->zipCode);
	  }

	  /**
		* sets the value of zipCode
		*
		* @param int $newZipCode for ZipCode
		* @throws UnexpectedValueException if not an integer
		* @throws RangeException if zipCode isn't positive
		**/
	  public function setZipCode($newZipCode)
	  {

		  //first, ensure the zip code is a number
		  $newZipCode = trim($newZipCode);
		  $verifyRegExp = array("options" => array("regexp" => "/^[\d]{5}(-[\d]{4})?$/"));
		  if(filter_var($newZipCode, FILTER_VALIDATE_REGEXP, $verifyRegExp) === false) {
			  throw(new UnexpectedValueException("Zip Code $newZipCode is not numeric"));
		  }


		  //remove Zip Code from quarantine
		  $this->zipCode = $newZipCode;
	  }

	  /**
		* inserts this Profile to mySQL
		*
		* @param resource $mysqli pointer to mySQL connection, by reference
		* @throws mysqli_sql_exception when mySQL related errors occur
		**/
	  public function insert(&$mysqli)
	  {
		  // handle degenerate cases
		  if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			  throw(new mysqli_sql_exception("input is not a mysqli object"));
		  }

		  // enforce the profileId is null (i.e., don't insert a user that already exists)
		  if($this->profileId !== null) {
			  throw(new mysqli_sql_exception("not a new user"));
		  }

		  // create query template
		  $query			= "INSERT INTO profile(userId, userTitle, firstName, midInit, lastName, bio, attention, street1,
														street2, city, state, zipCode) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		  $statement	= $mysqli->prepare($query);
		  if($statement === false) {
			  throw(new mysqli_sql_exception("Unable to prepare statement"));
		  }

		  // bind the member variables to the place holders in the template
		  $wasClean = $statement->bind_param("isssssssssss", $this->userId, $this->userTitle, $this->firstName, $this->midInit,
			  																  $this->lastName, $this->bio, $this->attention, $this->street1,
			  																  $this->street2, $this->city, $this->state, $this->zipCode);
		  if($wasClean === false) {
			  throw(new mysqli_sql_exception("Unable to bind parameters"));
		  }

		  // execute the statement
		  if($statement->execute() === false) {
			  throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		  }

		  // update the primary key
		  $this->profileId = $mysqli->insert_id;

	  }

	  /**
		* deletes this Profile from mySQL
		*
		* @param resource $mysqli pointer to mySQL connection, by reference
		* @throws mysqli_sql_exception when mySQL related errors occur
		**/
	  public function delete(&$mysqli) {
		  // handle degenerate cases
		  if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			  throw(new mysqli_sql_exception("input is not a mysqli object"));
		  }

		  // enforce the profileId is not null (i.e., don't delete a user that hasn't been inserted)
		  if($this->profileId === null) {
			  throw(new mysqli_sql_exception("Unable to delete a user that does not exist"));
		  }

		  // create query template
		  $query     = "DELETE FROM profile WHERE profileId = ?";
		  $statement = $mysqli->prepare($query);
		  if($statement === false) {
			  throw(new mysqli_sql_exception("Unable to prepare statement"));
		  }

		  // bind the member variables to the place holder in the template
		  $wasClean = $statement->bind_param("i", $this->profileId);
		  if($wasClean === false) {
			  throw(new mysqli_sql_exception("Unable to bind parameters"));
		  }

		  // execute the statement
		  if($statement->execute() === false) {
			  throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		  }
	  }

	  /**
		* updates this Profile in mySQL
		*
		* @param resource $mysqli pointer to mySQL connection, by reference
		* @throws mysqli_sql_exception when mySQL related errors occur
		**/
	  public function update(&$mysqli) {
		  // handle degenerate cases
		  if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			  throw(new mysqli_sql_exception("input is not a mysqli object"));
		  }

		  // enforce the profileId is not null (i.e., don't update a user that hasn't been inserted)
		  if($this->profileId === null) {
			  throw(new mysqli_sql_exception("Unable to update a user that does not exist"));
		  }

		  // create query template
		  $query     = "UPDATE profile SET userId = ?, userTitle = ?, firstName = ?, midInit = ?, lastName = ?, bio = ?,
													  attention = ?, street1 =?, street2 =?, city = ?, state = ?, zipCode = ?
 							 WHERE profileId = ?";
		  $statement = $mysqli->prepare($query);
		  if($statement === false) {
			  throw(new mysqli_sql_exception("Unable to prepare statement"));
		  }

		  // bind the member variables to the place holders in the template
		  $wasClean = $statement->bind_param("isssssssssssi", $this->userId, $this->userTitle, $this->firstName, $this->midInit,
			  																  $this->lastName, $this->bio, $this->attention, $this->street1,
			  																  $this->street2, $this->city, $this->state, $this->zipCode,
			  																  $this->profileId);
		  if($wasClean === false) {
			  throw(new mysqli_sql_exception("Unable to bind parameters"));
		  }

		  // execute the statement
		  if($statement->execute() === false) {
			  throw(new mysqli_sql_exception("Unable to execute mySQL statement"));
		  }
	  }
	  /**
		* gets the Profile by profileId
		*
		* @param resource $mysqli pointer to mySQL connection, by reference
		* @param int $profileId profileId to search for
		* @return mixed Profile found or null if not found
		* @throws mysqli_sql_exception when mySQL related errors occur
		**/

	  public static function getProfileByProfileId(&$mysqli, $profileId)
	  {
		  //handle degenerate cases
		  if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			  throw(new mysqli_sql_exception("input is not a mysqli object"));
		  }
		  //sanitize the profileId before searching
		  $profileId = filter_var($profileId, FILTER_VALIDATE_INT);
		  if($profileId === null) {
			  throw(new mysqli_sql_exception("input is null"));
		  }

		  //Create query template
		  $query = "SELECT profileId, userId, userTitle, firstName, midInit, lastName, bio, attention, street1, street2,
			  					 city, state, zipCode FROM profile WHERE profileId = ?";
		  $statement = $mysqli->prepare($query);
		  if($statement === false) {
			  throw(new mysqli_sql_exception ("unable to prepare statement"));
		  }

		  //bind the profile Id to the place holder in the template
		  $wasClean = $statement->bind_param("i", $profileId);
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
				  $profile = new profile($row["profileId"], $row["userId"], $row["userTitle"], $row["firstName"], $row["midInit"],
					  							 $row["lastName"], $row["bio"], $row["attention"], $row["street1"], $row["street2"],
					  							 $row["city"], $row["state"], $row["zipCode"]);
			  } catch(Exception $exception) {
				  //rethrow
				  throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));

			  }
			  //if we get a profileId I'm lucky and show it
			  return ($profile);
		  } else {
			  //404 User not found
			  return (null);
		  }
	  }

  /** gets the Profile by userId
  *
  * @param resource $mysqli pointer to mySQL connection, by reference
  * @param int $userId userId to search for
  * @return int userID found
  * @throws mysqli_sql_exception when mySQL related errors occur
  **/

	  public static function getProfileByUserId(&$mysqli, $userId)
	  {
		  //handle degenerate cases
		  if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			  throw(new mysqli_sql_exception("input is not a mysqli object"));
		  }
		  //sanitize the userId before searching
		  $userId = trim($userId);
		  $userId = filter_var($userId, FILTER_VALIDATE_INT);
		  $userId = intval($userId);

		  //Create query template
		  $query = "SELECT profileId, userId, userTitle, firstName, midInit, lastName, bio, attention, street1, street2,
			  					 city, state, zipCode FROM profile WHERE userId = ?";
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

		  //covert the associative array to a profileId
		  if($row !== null) {
			  try {
				  $profile = new profile($row["profileId"], $row["userId"], $row["userTitle"], $row["firstName"], $row["midInit"],
					  $row["lastName"], $row["bio"], $row["attention"], $row["street1"], $row["street2"],
					  $row["city"], $row["state"], $row["zipCode"]);
			  } catch(Exception $exception) {
				  //rethrow
				  throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));

			  }
			  //if we get a profileId I'm lucky and show it
			  return ($profile);
		  } else {
			  //404 User not found
			  return (null);
		  }
	  }

	  /**
		* gets the Profile by zipCode
		*
		* @param resource $mysqli pointer to mySQL connection, by reference
		* @param int $zipCode  zipCode to search for
		* @return int Profile found or null if not found
		* @throws mysqli_sql_exception when mySQL related errors occur
		**/
	  public static function getProfileByZipCode(&$mysqli, $zipCode) {

		  //handle degenerate cases
		  if(gettype($mysqli) !== "object" || get_class($mysqli) !== "mysqli") {
			  throw(new mysqli_sql_exception("input is not a mysqli object"));
		  }
		  //sanitize the zipCode before searching
		  $zipCode = trim($zipCode);
		  $zipCode = filter_var($zipCode, FILTER_VALIDATE_INT);
		  $zipCode = intval($zipCode);
		  if($zipCode === null) {
			  throw(new mysqli_sql_exception("input is null"));
		  }

		  //Create query template
		  $query = "SELECT profileId, userId, userTitle, firstName, midInit, lastName, bio, attention, street1, street2,
			  					 city, state, zipCode FROM profile WHERE zipCode = ?";
		  $statement = $mysqli->prepare($query);
		  if($statement === false) {
			  throw(new mysqli_sql_exception ("unable to prepare statement"));
		  }

		  //bind the profile Id to the place holder in the template
		  $wasClean = $statement->bind_param("i", $zipCode);
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

		  //many users can have different zipCodes
		  //if there's a result, we can show it
		  //if not error code 404

		  //userArrayCounter = 0
		  $profileArray = array();
		  while(($row = $result->fetch_assoc()) !== null) {

			  //covert the associative array to a userId and repeat for all zipCodes
			  try {
				  $profile = new profile($row["profileId"], $row["userId"], $row["userTitle"], $row["firstName"], $row["midInit"],
					  $row["lastName"], $row["bio"], $row["attention"], $row["street1"], $row["street2"],
					  $row["city"], $row["state"], $row["zipCode"]);
				  //build empty array for sql to fill
				  $profileArray [] = $profile;

			  } catch(Exception $exception) {
				  //rethrow
				  throw(new mysqli_sql_exception ("unable to convert row to user", 0, $exception));
			  }
		  }
		  //if we get a userId I'm lucky and show it
		  if ($result->num_rows ===0) {
			  return (null);
		  } else {

			  return ($profileArray);
		  }
	  }






  }