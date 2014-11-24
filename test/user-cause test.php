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
	private $causeUser = null;

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
		$i = rand(1, 1000);
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
		if($this->causeUser !== null) {
			$this->causeUser->delete($this->mysqli);
			$this->causeUser = null;
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

		// second, create a causeUser to post to mySQL
		$this->causeUser = new UserCause($this->profile->getProfileId(), $this->cause->getCauseId());

		// third, insert the causeUser, to mySQL
		$this->causeUser->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->causeUser->profileId);
		$this->assertTrue($this->causeUser->profileId > 0);
		$this->assertNotNull($this->causeUser->causeId);
		$this->assertTrue($this->causeUser->causeId > 0);
		$this->assertIdentical($this->causeUser->profileId,							$this->profile->getProfileId());
		$this->assertIdentical($this->causeUser->causeId,								$this->cause->getCauseId());

	}

	// test deleting a CommentUser
	public function testDeleteUserCause() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a CommentUser to post to mySQL
		$this->causeUser = new UserCause($this->profile->getProfileId(), $this->cause->getCauseId());

		// third, insert the CommentUser to mySQL
		$this->causeUser->insert($this->mysqli);

		// fourth, verify the CommentUser was inserted
		$this->assertNotNull($this->causeUser->profileId);
		$this->assertTrue($this->causeUser->profileId > 0);
		$this->assertNotNull($this->causeUser->causeId);
		$this->assertTrue($this->causeUser->causeId > 0);

		// fifth, delete the causeUser
		$this->causeUser->delete($this->mysqli);
		$this->causeUser = null;

		// finally, try to get the userTeam and assert we didn't get a thing
		$hopefulCauseUserId = UserCause::getUserCauseByUserCauseId($this->mysqli, $this->profile->getProfileId(), $this->cause->getCauseId());
		$this->assertNull($hopefulCauseUserId);
	}
	// test grabbing a userTeam from mySQL
	public function testGetUserCauseByProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a causeUser to post to mySQL
		$this->causeUser = new UserCause($this->profile->getProfileId(), $this->cause->getCauseId());

		// third, insert the CommentUser to mySQL
		$this->causeUser->insert($this->mysqli);

		// fourth, get the causeUser using the static method
		$staticCauseUser = UserCause::getUserCauseByUserCauseId($this->mysqli, $this->causeUser->profileId,
			$this->causeUser->causeId);

		// finally, compare the fields to upload
		$this->assertNotNull($staticCauseUser->profileId);
		$this->assertTrue($staticCauseUser->profileId > 0);
		$this->assertNotNull($staticCauseUser->causeId);
		$this->assertTrue($staticCauseUser->causeId > 0);
		$this->assertIdentical($staticCauseUser->profileId,							$this->profile->getProfileId());
		$this->assertIdentical($staticCauseUser->causeId,							$this->cause->getCauseId());
	}


}