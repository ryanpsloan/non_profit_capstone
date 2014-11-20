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
		$this->user = new User(null,"Juan", "Juanito".$i."@gmail.com",$passwordHash,$salt,$authToken,2);
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
	public function testInsertNewCommentUser() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a causeUser to post to mySQL
		$this->causeUser = new UserCause($this->profile->getProfileId(), $this->cause->getCauseId());

		// third, insert the causeUser, to mySQL
		$this->causeUser->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->causeUser->getProfileId());
		$this->assertTrue($this->causeUser->getProfileId() > 0);
		$this->assertNotNull($this->causeUser->getCommentId());
		$this->assertTrue($this->causeUser->getCommentId() > 0);
		$this->assertIdentical($this->causeUser->getProfileId(),							$this->profile->getProfileId());
		$this->assertIdentical($this->causeUser->getCommentId(),							$this->cause->getCauseId());

	}

	// test deleting a CommentUser
	public function testDeleteCauseUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a CommentUser to post to mySQL
		$this->causeUser = new CommentUser($this->profile->getProfileId(), $this->cause->causeId);

		// third, insert the CommentUser to mySQL
		$this->causeUser->insert($this->mysqli);

		// fourth, verify the CommentUser was inserted
		$this->assertNotNull($this->causeUser->getProfileId());
		$this->assertTrue($this->causeUser->getProfileId() > 0);
		$this->assertNotNull($this->causeUser->getCommentId());
		$this->assertTrue($this->causeUser->getCommentId() > 0);

		// fifth, delete the causeUser
		$this->causeUser->delete($this->mysqli);
		$this->causeUser = null;

		// finally, try to get the userTeam and assert we didn't get a thing
		$hopefulUserCommentId = CommentUser::getCommentUserByProfileCommentId ($this->mysqli, $this->profile->getProfileId(), $this->cause->causeId);
		$this->assertNull($hopefulUserCommentId);
	}
	// test grabbing a userTeam from mySQL
	public function testGetCommentUserByProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a causeUser to post to mySQL
		$this->causeUser = new CommentUser($this->profile->getProfileId(), $this->cause->causeId);

		// third, insert the CommentUser to mySQL
		$this->causeUser->insert($this->mysqli);

		// fourth, get the causeUser using the static method
		$staticCommentUser = CommentUser::getCommentUserByProfileCommentId($this->mysqli, $this->causeUser->getProfileId(),
			$this->causeUser->getCommentId());

		// finally, compare the fields to upload
		$this->assertNotNull($staticCommentUser->getProfileId());
		$this->assertTrue($staticCommentUser->getProfileId() > 0);
		$this->assertNotNull($staticCommentUser->getCommentId());
		$this->assertTrue($staticCommentUser->getCommentId() > 0);
		$this->assertIdentical($staticCommentUser->getProfileId(),							$this->profile->getProfileId());
		$this->assertIdentical($staticCommentUser->getCommentId(),							$this->cause->causeId);
	}


}