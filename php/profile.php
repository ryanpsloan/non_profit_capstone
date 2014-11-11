  <?php
/**
 *mySQL Enabled Profile
 *
 * This is a mySQL enabled container for profile identification at a nonprofit site.
 *
 * User: Martin
 **/

//setup Profile Class and respective fields
  class Profile {

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

	  //zipcode for profile
	  private $zipCode;

	/** constructor for the Profile
	 *
	 * @param mixed $newProfileId for profileId(or null if new object)
	 * @param int $newUserId  for UserId
	 * @param string $newUserTitle for userTitle
	 * @param string $newFirstName for firstName
	 * @param string $newMidInit for middleInitial
	 * @param string $newLastName for lastName
	 * @param string $newBio for biography
	 * @param string $newAttention for attention
	 * @param string $newStreet1 for street1
	 * @param string $newStreet2 for street2
	 * @param string $newCity for city
	 * @param string $newState for state
	 * @param int $newZipCode for Zip Code
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/

	public function __construct($newProfileId, $newUserId, $newUserTitle, $newFirstName, $newMidInit, $newLastName,
										 $newBio, $newAttention, $newStreet1, $newStreet2, $newCity, $newState, $newZipCode){
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
			throw(new UnexpectedValueException ("Unable to construct User", 0, $unexpectedValue));

		} catch(RangeException $range) {
			throw(new RangeException("Unable to construct User", 0, $range));
		}

	}
	  /**
		* gets the value of profileId
		*
		* @return mixed profileId (or null if new object)
		**/
	  public function getProfileId() {
		  return($this->profileId);
	  }

	  /**
		* sets the value of profile id
		*
		* @param string $newProfileId profile id (or null if new object)
		* @throws UnexpectedValueException if not an integer or null
		* @throws RangeException if user id isn't positive
		**/
	  public function setProfileId($newProfileId) {
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
	  /** Gets the value of contact Id
		*
		* @return  int value for contact Id
		* @throws UnexpectedValueException if not an integer or null
		* @throws RangeException if user id isn't positive
		**/
	  public function getUserId () {
		  return($this->UserId);
	  }
	  //verify that user id is an integer
	  public function setUserId ($newUserId) {
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
	  public function getUserTitle () {
		  return($this->userTitle);
	  }

	  /**
		* sets the value of user Title
		*
		* @param string $newUserTitle for userTitle
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if user name is longer than 64 characters
		**/

	  //verify userTitle is a string
	  public function setUserTitle($newUserTitle) {
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
	  public function getFirstName () {
		  return($this->firstName);
	  }

	  /**
		* sets the value of firstName
		*
		* @param string $newFirstName for firstName
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if firstName is longer than 32 characters
		**/

	  //verify firstName is a string
	  public function setFirstName($newFirstName) {
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
	  public function getMidInit () {
		  return($this->midInit);
	  }

	  /**
		* sets the value of midInit
		*
		* @param string $newMidInit for midInit
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if midInit is longer than 2 characters
		**/

	  //verify midInit is a string
	  public function setMidInit($newMidInit) {
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
	  public function getlastName () {
		  return($this->lastName);
	  }

	  /**
		* sets the value of lastName
		*
		* @param string $newLastName for lastName
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if lastName is longer than 32 characters
		**/

	  //verify lastName is a string
	  public function setLastName($newLastName) {
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
	  public function getBio () {
		  return($this->bio);
	  }

	  /**
		* sets the value of bio
		*
		* @param string $newBio for bio
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if bio is longer than 1000 characters
		**/

	  //verify bio is a string
	  public function setBio($newBio) {
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
	  public function getAttention () {
		  return($this->attention);
	  }

	  /**
		* sets the value of attention
		*
		* @param string $newAttention for attention
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if attention is longer than 64 characters
		**/

	  //verify attention is a string
	  public function setAttention($newAttention) {
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
	  public function getStreet1 () {
		  return($this->Street1);
	  }

	  /**
		* sets the value of street1
		*
		* @param string $newStreet1 for street1
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if street1 is longer than 64 characters
		**/

	  //verify street1 is a string
	  public function setStreet1($newStreet1) {
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
	  public function getStreet2 () {
		  return($this->Street2);
	  }

	  /**
		* sets the value of street2
		*
		* @param string $newStreet2 for street2
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if street2 is longer than 64 characters
		**/

	  //verify street2 is a string
	  public function setStreet2($newStreet2) {
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
	  public function getCity () {
		  return($this->city);
	  }

	  /**
		* sets the value of city
		*
		* @param string $newCity for city
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if city is longer than 64 characters
		**/

	  //verify city is a string
	  public function setCity($newCity) {
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
	  public function getState () {
		  return($this->state);
	  }

	  /**
		* sets the value of state
		*
		* @param string $newState for midState
		* @throws UnexpectedValueException if not a string
		* @throws RangeException if state is longer than 2 characters
		**/

	  //verify state is a string
	  public function setState($newState) {
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
	  public function getZipCode() {
		  return($this->zipCode);
	  }

	  /**
		* sets the value of zipCode
		*
		* @param int $newZipCode for ZipCode
		* @throws UnexpectedValueException if not an integer
		* @throws RangeException if zipCode isn't positive
		**/
	  public function setZipCode($newZipCode) {

		  //first, ensure the zip code is an integer
		  $newZipCode = trim($newZipCode);
		  if(filter_var($newZipCode, FILTER_VALIDATE_INT) === false) {
			  throw(new UnexpectedValueException("Zip Code $newZipCode is not numeric"));
		  }

		  // second, convert the zipCode to an integer and enforce it's positive
		  $newZipCode = intval($newZipCode);
		  if($newZipCode <= 0) {
			  throw(new RangeException ("Zip Code $newZipCode is not positive"));
		  }
		  //remove Zip Code from quarantine
		  $this->zipCode = $newZipCode;
	  }
  }