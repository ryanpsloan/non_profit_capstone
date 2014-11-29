<?php
/**
 * Unit test for User Id class
 * User: Martin
 * Date: 11/11/2014
 * Time: 1:24 PM
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/user.php");

//then require class under scrutiny
require_once("../php/profile.php");

//the ProfileIdTest for all our testsclass ProfileIdTest extends UnitTestCase {
class ProfileIdTest extends UnitTestCase
{

	//variable to hold the test database row
	private $mysqli = null;
	
	//variable to hold the test database row
	private $profile = null;
	private $profile1 = null;
	
	//the rest of the "global" variables used to create test data
	private $USERTITLE = "Mr.";
	private $FIRSTNAME = "Edward";
	private $MIDINIT = "J";
	private $LASTNAME = "Scissorhands";
	private $BIO = "I really love giving free haircuts to willing customers";
	private $ATTENTION = "Free Cut";
	private $STREET1 = "1874 Baldhead Lane";
	private $STREET2 = "Apt 24";
	private $CITY = "Albuquerque";
	private $STATE = "NM";
	private $ZIPCODE = "87171";

	private $user = null;
	private $user1 = null;



	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp() {
		//connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

		$salt       = bin2hex(openssl_random_pseudo_bytes(32));
		$authToken = bin2hex(openssl_random_pseudo_bytes(16));
		$passwordHash       = hash_pbkdf2("sha512", "password", $salt, 2048, 128);

		$this->user = new User(null, "imconfused", "myhomie@lost.com", $passwordHash, $salt, $authToken, 2);
		$this->user->insert($this->mysqli);

		$this->user1 = new User(null, "hentry", "myotherhomie@lost.com", $passwordHash, $salt, $authToken, 1);
		$this->user1->insert($this->mysqli);
	}

	//Teardown (), a method to delete the test record and disconnect from mySQL
	public function tearDown()
	{
		// delete the user if we can
		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}
		if($this->user !==null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}

		if($this->user1 !==null) {
			$this->user1->delete($this->mysqli);
			$this->user1 = null;
		}
	}
	// test creating a new Profile Id and inserting it to mySQL
	public function testInsertNewProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a profile to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->USERTITLE, $this->FIRSTNAME, $this->MIDINIT, $this->LASTNAME,
													  $this->BIO, $this->ATTENTION, $this->STREET1, $this->STREET2, $this->CITY,
													  $this->STATE, $this->ZIPCODE);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertIdentical($this->profile->getUserId(),	               	$this->user->getUserId());
		$this->assertIdentical($this->profile->getUserTitle(),            		$this->USERTITLE);
		$this->assertIdentical($this->profile->getFirstName(),						$this->FIRSTNAME);
		$this->assertIdentical($this->profile->getMidInit(),							$this->MIDINIT);
		$this->assertIdentical($this->profile->getLASTNAME(),							$this->LASTNAME);
		$this->assertIdentical($this->profile->getBio(),								$this->BIO);
		$this->assertIdentical($this->profile->getAttention(),						$this->ATTENTION);
		$this->assertIdentical($this->profile->getStreet1(),							$this->STREET1);
		$this->assertIdentical($this->profile->getStreet2(),							$this->STREET2);
		$this->assertIdentical($this->profile->getCity(),								$this->CITY);
		$this->assertIdentical($this->profile->getState(),								$this->STATE);
		$this->assertIdentical($this->profile->getZipCode(),							$this->ZIPCODE);
	}
// test updating a Profile in mySQL
	public function testUpdateProfileId() {

		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a profile to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->USERTITLE, $this->FIRSTNAME, $this->MIDINIT, $this->LASTNAME,
												$this->BIO, $this->ATTENTION, $this->STREET1, $this->STREET2, $this->CITY,
												$this->STATE, $this->ZIPCODE);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, update the user and post the changes to mySQL
		$newZipCode = "12345";
		$this->profile->setZipCode($newZipCode);
		$this->profile->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertIdentical($this->profile->getUserId(),	               	$this->user->getUserId());
		$this->assertIdentical($this->profile->getUserTitle(),            		$this->USERTITLE);
		$this->assertIdentical($this->profile->getFirstName(),						$this->FIRSTNAME);
		$this->assertIdentical($this->profile->getMidInit(),							$this->MIDINIT);
		$this->assertIdentical($this->profile->getLASTNAME(),							$this->LASTNAME);
		$this->assertIdentical($this->profile->getBio(),								$this->BIO);
		$this->assertIdentical($this->profile->getAttention(),						$this->ATTENTION);
		$this->assertIdentical($this->profile->getStreet1(),							$this->STREET1);
		$this->assertIdentical($this->profile->getStreet2(),							$this->STREET2);
		$this->assertIdentical($this->profile->getCity(),								$this->CITY);
		$this->assertIdentical($this->profile->getState(),								$this->STATE);
		$this->assertIdentical($this->profile->getZipCode(),							$newZipCode);
	}
	// test deleting a Profile Id
	public function testDeleteProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a Profile to post to mySQL
		$this-> profile = new Profile(null, $this->user->getUserId(), $this->USERTITLE, $this->FIRSTNAME, $this->MIDINIT, $this->LASTNAME,
			$this->BIO, $this->ATTENTION, $this->STREET1, $this->STREET2, $this->CITY,
			$this->STATE, $this->ZIPCODE);

		// third, insert the Profile to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, verify the Profile was inserted
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);

		// fifth, delete the profile
		$this->profile->delete($this->mysqli);
		$this->profile = null;

		// finally, try to get the user id and assert we didn't get a thing
		$hopefulProfileId = Profile::getProfileByProfileId($this->mysqli, $this->profile);
		$this->assertNull($hopefulProfileId);
	}

// test grabbing a user id from mySQL
	public function testGetUserByProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a profile to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->USERTITLE, $this->FIRSTNAME, $this->MIDINIT, $this->LASTNAME,
			$this->BIO, $this->ATTENTION, $this->STREET1, $this->STREET2, $this->CITY,
			$this->STATE, $this->ZIPCODE);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, get the profile using the static method
		$staticProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile->getProfileId());

		// finally, compare the fields
		$this->assertNotNull($staticProfile->getProfileId ());
		$this->assertTrue($staticProfile->getProfileId () > 0);
		$this->assertIdentical($staticProfile->getProfileId(),		 				$this->profile->getProfileId());
		$this->assertIdentical($staticProfile->getUserId(),	               	$this->user->getUserId());
		$this->assertIdentical($staticProfile->getUserTitle(),            		$this->USERTITLE);
		$this->assertIdentical($staticProfile->getFirstName(),						$this->FIRSTNAME);
		$this->assertIdentical($staticProfile->getMidInit(),							$this->MIDINIT);
		$this->assertIdentical($staticProfile->getLASTNAME(),							$this->LASTNAME);
		$this->assertIdentical($staticProfile->getBio(),								$this->BIO);
		$this->assertIdentical($staticProfile->getAttention(),						$this->ATTENTION);
		$this->assertIdentical($staticProfile->getStreet1(),							$this->STREET1);
		$this->assertIdentical($staticProfile->getStreet2(),							$this->STREET2);
		$this->assertIdentical($staticProfile->getCity(),								$this->CITY);
		$this->assertIdentical($staticProfile->getState(),								$this->STATE);
		$this->assertIdentical($staticProfile->getZipCode(),							$this->ZIPCODE);
	}

	// test grabbing a profile by user Id from mySQL
	public function testGetUserByUserId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a profile to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->USERTITLE, $this->FIRSTNAME, $this->MIDINIT, $this->LASTNAME,
			$this->BIO, $this->ATTENTION, $this->STREET1, $this->STREET2, $this->CITY,
			$this->STATE, $this->ZIPCODE);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, get the profile using the static method
		$staticProfile = Profile::getProfileByUserId($this->mysqli, $this->profile->getUserId());

		// finally, compare the fields
		$this->assertNotNull($staticProfile->getProfileId ());
		$this->assertTrue($staticProfile->getProfileId () > 0);
		$this->assertIdentical($staticProfile->getProfileId(),		 				$this->profile->getProfileId());
		$this->assertIdentical($staticProfile->getUserId(),	               	$this->user->getUserId());
		$this->assertIdentical($staticProfile->getUserTitle(),            		$this->USERTITLE);
		$this->assertIdentical($staticProfile->getFirstName(),						$this->FIRSTNAME);
		$this->assertIdentical($staticProfile->getMidInit(),							$this->MIDINIT);
		$this->assertIdentical($staticProfile->getLASTNAME(),							$this->LASTNAME);
		$this->assertIdentical($staticProfile->getBio(),								$this->BIO);
		$this->assertIdentical($staticProfile->getAttention(),						$this->ATTENTION);
		$this->assertIdentical($staticProfile->getStreet1(),							$this->STREET1);
		$this->assertIdentical($staticProfile->getStreet2(),							$this->STREET2);
		$this->assertIdentical($staticProfile->getCity(),								$this->CITY);
		$this->assertIdentical($staticProfile->getState(),								$this->STATE);
		$this->assertIdentical($staticProfile->getZipCode(),							$this->ZIPCODE);
	}

	// test grabbing a profile by ZIPCODE from mySQL

	public function testGetUserTeamByZipCode() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a userTeam to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->USERTITLE, $this->FIRSTNAME, $this->MIDINIT, $this->LASTNAME,
											  $this->BIO, $this->ATTENTION, $this->STREET1, $this->STREET2, $this->CITY, $this->STATE, $this->ZIPCODE);
		$this->profile1 = new Profile(null, $this->user->getUserId(), $this->USERTITLE, $this->FIRSTNAME, $this->MIDINIT, $this->LASTNAME,
											  $this->BIO, $this->ATTENTION, $this->STREET1, $this->STREET2, $this->CITY, $this->STATE, $this->ZIPCODE);

		// third, insert the userTeam to mySQL
		$this->profile->insert($this->mysqli);
		$this->profile1->insert($this->mysqli);

		// fourth, get the userTeam using the static method
		$staticProfile = Profile::getProfileByZipCode($this->mysqli, $this->ZIPCODE);

		// finally, compare the fields
		for($i = 0; $i < count($staticProfile); $i++){
			$this->assertNotNull($staticProfile[$i]->getProfileId());
			$this->assertTrue($staticProfile[$i]->getProfileId() > 0);
			$this->assertIdentical($staticProfile[$i]->getZipCode(), $this->ZIPCODE);
		}
		//teardown for userTeam1
		$this->profile1->delete($this->mysqli);
	}

}