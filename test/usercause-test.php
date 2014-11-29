<?php
/**
 * Unit test for UserCause class
 * 
 */

// Require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/user.php");
require_once("../php/profile.php");
require_once("../php/cause.php");

//Require class under scrutiny
require_once("../php/usercause.php");

//the UserCause container for all the tests
class UserCauseTest extends UnitTestCase {

	//variable to hold the mySQL connection
	private $mysqli = null;

	//variable to hold the test database
	private $userCause = null;

	//the "global" variables to create test data

	private $user = null;
	private $profile = null;
	private $cause = null;

	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp() {

		//connect to mySQL and add data
		$this->mysqli = MysqliConfiguration::getMysqli();

		$salt       = bin2hex(openssl_random_pseudo_bytes(32));
		$authToken = bin2hex(openssl_random_pseudo_bytes(16));
		$passwordHash       = hash_pbkdf2("sha512", "password", $salt, 2048, 128);
		$this->user = new User(null,"Juan", "Juanito@gmail.com",$passwordHash,$salt,$authToken,2);
		$this->user->insert($this->mysqli);

		$this->profile = new Profile(null, $this->user->getUserId(),"Sr.","Juan", "A", "Garcia", "I have the largest pen", "PM of Britain", "Address", "SW", "City", "NM", "87144");
		$this->profile->insert($this->mysqli);

		$this->cause = new Cause(null, "Homeless coders", "no homes");
		$this->cause->insert($this->mysqli);
	}

	//Tear down (), a method to delete the test record and disconnect from mySQL
	public function tearDown(){

		// delete the user if we can
		if($this->userCause !== null) {
			$this->userCause->delete($this->mysqli);
			$this->userCause = null;
		}

		if($this->cause !== null) {
			$this->cause->delete($this->mysqli);
			$this->cause = null;
		}

		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}

		if($this->user !== null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}
	}

	// test creating a new profile Id and cause Id and inserting it to mySQL
	public function testInsertNewUserCause() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a userUserCause to post to mySQL
		$this->userCause = new UserCause($this->profile->getProfileId(), $this->cause->getCauseId());

		// third, insert the userCause, to mySQL
		$this->userCause->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->userCause->profileId);
		$this->assertTrue($this->userCause->profileId > 0);
		$this->assertNotNull($this->userCause->causeId);
		$this->assertTrue($this->userCause->causeId > 0);
		$this->assertIdentical($this->userCause->profileId,							$this->profile->getProfileId());
		$this->assertIdentical($this->userCause->causeId,								$this->cause->getCauseId());

	}

	// test deleting a  userCause
	public function testDeleteUserCause() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a userCause to post to mySQL
		$this->userCause = new UserCause($this->profile->getProfileId(), $this->cause->getCauseId());

		// third, insert the userCause to mySQL
		$this->userCause->insert($this->mysqli);

		// fourth, verify the userCause was inserted
		$this->assertNotNull($this->userCause->profileId);
		$this->assertTrue($this->userCause->profileId > 0);
		$this->assertNotNull($this->userCause->causeId);
		$this->assertTrue($this->userCause->causeId > 0);

		// fifth, delete the userCause
		$this->userCause->delete($this->mysqli);
		$this->userCause = null;

		// finally, try to get the userCause and assert we didn't get a thing
		$hopefulUserCauseId = UserCause::getUserCauseByUserCauseId($this->mysqli, $this->profile->getProfileId(), $this->cause->getCauseId());
		$this->assertNull($hopefulUserCauseId);
	}
	// test grabbing a userCause from mySQL
	public function testGetUserCauseByProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a userCause to post to mySQL
		$this->userCause = new UserCause($this->profile->getProfileId(), $this->cause->getCauseId());

		// third, insert the userCause to mySQL
		$this->userCause->insert($this->mysqli);

		// fourth, get the userCause using the static method
		$staticUserCause = UserCause::getUserCauseByUserCauseId($this->mysqli, $this->userCause->profileId,
			$this->userCause->causeId);

		// finally, compare the fields to upload
		$this->assertNotNull($staticUserCause->profileId);
		$this->assertTrue($staticUserCause->profileId > 0);
		$this->assertNotNull($staticUserCause->causeId);
		$this->assertTrue($staticUserCause->causeId > 0);
		$this->assertIdentical($staticUserCause->profileId,							$this->profile->getProfileId());
		$this->assertIdentical($staticUserCause->causeId,							$this->cause->getCauseId());
	}
}