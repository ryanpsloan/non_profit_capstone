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
	//the rest of the "global" variables used to create test data
	private $userTitle = "Mr.";
	private $firstName = "Edward";
	private $midInit = "J";
	private $lastName = "Scissorhands";
	private $bio = "I really love giving free haircuts to willing customers";
	private $attention = "Free Cut";
	private $street1 = "1874 Baldhead Lane";
	private $street2 = "Apt 24";
	private $city = "Albuquerque";
	private $state = "NM";
	private $zipCode = "87171";

	private $user = null;


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
	}
	// test creating a new Profile Id and inserting it to mySQL
	public function testInsertNewProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a profile to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->userTitle, $this->firstName, $this->midInit, $this->lastName,
													  $this->bio, $this->attention, $this->street1, $this->street2, $this->city,
													  $this->state, $this->zipCode);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertIdentical($this->profile->getUserId(),	               	$this->user->getUserId());
		$this->assertIdentical($this->profile->getUserTitle(),            		$this->userTitle);
		$this->assertIdentical($this->profile->getFirstName(),						$this->firstName);
		$this->assertIdentical($this->profile->getMidInit(),							$this->midInit);
		$this->assertIdentical($this->profile->getlastName(),							$this->lastName);
		$this->assertIdentical($this->profile->getBio(),								$this->bio);
		$this->assertIdentical($this->profile->getAttention(),						$this->attention);
		$this->assertIdentical($this->profile->getStreet1(),							$this->street1);
		$this->assertIdentical($this->profile->getStreet2(),							$this->street2);
		$this->assertIdentical($this->profile->getCity(),								$this->city);
		$this->assertIdentical($this->profile->getState(),								$this->state);
		$this->assertIdentical($this->profile->getZipCode(),							$this->zipCode);
	}
// test updating a Profile in mySQL
	public function testUpdateProfileId() {

		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a profile to post to mySQL
		$this->profile = new Profile(null, $this->user->getUserId(), $this->userTitle, $this->firstName, $this->midInit, $this->lastName,
												$this->bio, $this->attention, $this->street1, $this->street2, $this->city,
												$this->state, $this->zipCode);

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
		$this->assertIdentical($this->profile->getUserTitle(),            		$this->userTitle);
		$this->assertIdentical($this->profile->getFirstName(),						$this->firstName);
		$this->assertIdentical($this->profile->getMidInit(),							$this->midInit);
		$this->assertIdentical($this->profile->getlastName(),							$this->lastName);
		$this->assertIdentical($this->profile->getBio(),								$this->bio);
		$this->assertIdentical($this->profile->getAttention(),						$this->attention);
		$this->assertIdentical($this->profile->getStreet1(),							$this->street1);
		$this->assertIdentical($this->profile->getStreet2(),							$this->street2);
		$this->assertIdentical($this->profile->getCity(),								$this->city);
		$this->assertIdentical($this->profile->getState(),								$this->state);
		$this->assertIdentical($this->profile->getZipCode(),							$newZipCode);
	}
	// test deleting a Profile Id
	public function testDeleteProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a Profile to post to mySQL
		$this-> profile = new Profile(null, $this->user->getUserId(), $this->userTitle, $this->firstName, $this->midInit, $this->lastName,
			$this->bio, $this->attention, $this->street1, $this->street2, $this->city,
			$this->state, $this->zipCode);

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
		$this->profile = new Profile(null, $this->user->getUserId(), $this->userTitle, $this->firstName, $this->midInit, $this->lastName,
			$this->bio, $this->attention, $this->street1, $this->street2, $this->city,
			$this->state, $this->zipCode);

		// third, insert the profile to mySQL
		$this->profile->insert($this->mysqli);

		// fourth, get the profile using the static method
		$staticProfile = Profile::getProfileByProfileId($this->mysqli, $this->profile->getProfileId());

		// finally, compare the fields
		$this->assertNotNull($staticProfile->getProfileId ());
		$this->assertTrue($staticProfile->getProfileId () > 0);
		$this->assertIdentical($staticProfile->getProfileId(),		 				$this->profile->getProfileId());
		$this->assertIdentical($staticProfile->getUserId(),	               	$this->user->getUserId());
		$this->assertIdentical($staticProfile->getUserTitle(),            		$this->userTitle);
		$this->assertIdentical($staticProfile->getFirstName(),						$this->firstName);
		$this->assertIdentical($staticProfile->getMidInit(),							$this->midInit);
		$this->assertIdentical($staticProfile->getlastName(),							$this->lastName);
		$this->assertIdentical($staticProfile->getBio(),								$this->bio);
		$this->assertIdentical($staticProfile->getAttention(),						$this->attention);
		$this->assertIdentical($staticProfile->getStreet1(),							$this->street1);
		$this->assertIdentical($staticProfile->getStreet2(),							$this->street2);
		$this->assertIdentical($staticProfile->getCity(),								$this->city);
		$this->assertIdentical($staticProfile->getState(),								$this->state);
		$this->assertIdentical($staticProfile->getZipCode(),							$this->zipCode);
	}

}