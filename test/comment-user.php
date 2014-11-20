<?php
/**
 * Unit test for CommentUser class
 * User: Martin
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/user.php");
require_once("../php/profile.php");
require_once("../php/comment.php");

//then require class under scrutiny
require_once("../php/commentUser.php");

//the UserTeamTest for all our testsclass UserTeamTest extends UnitTestCase {
class CommentUserTest extends UnitTestCase{

	//variable to hold the test database row
	private $mysqli = null;
	//variable to hold the test database row
	private $commentUser = null;

	//the rest of the "global" variables used to create test data

	private $user = null;
	private $profile = null;
	private $comment = null;


	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp()
	{
		//connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

		$salt       = bin2hex(openssl_random_pseudo_bytes(32));
		$authToken = bin2hex(openssl_random_pseudo_bytes(16));
		$passwordHash       = hash_pbkdf2("sha512", "password", $salt, 2048, 128);
		$i = rand(1, 1000);
		$this->user = new User(null,"igotthis", "myhomie".$i."@yahoo.com",$passwordHash,$salt,$authToken,2);
		$this->user->insert($this->mysqli);

		$this->profile = new Profile(null, $this->user->getUserId(),"Mr.","John", "P", "Handcock", "I have the largest signature on the
				Declaration of Independence", "PM of Britain", "1600 Pennsylvania Ave", "SW", "Washington", "DC", "20500");
		$this->profile->insert($this->mysqli);


		$this->comment = new Comment(null, "waiting on unit test", new DateTime());
		$this->comment->insert($this->mysqli);
	}

	//Teardown (), a method to delete the test record and disconnect from mySQL
	public function tearDown(){
		// delete the user if we can
		if($this->commentUser !== null) {
			$this->commentUser->delete($this->mysqli);
			$this->commentUser = null;
		}

		if($this->comment !== null) {
			$this->comment->delete($this->mysqli);
			$this->comment = null;
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

	// test creating a new profile Id and comment Id and inserting it to mySQL
	public function testInsertNewCommentUser() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentUser to post to mySQL
		$this->commentUser = new CommentUser($this->profile->getProfileId(), $this->comment->commentId);

		// third, insert the commentUser, to mySQL
		$this->commentUser->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->commentUser->getProfileId());
		$this->assertTrue($this->commentUser->getProfileId() > 0);
		$this->assertNotNull($this->commentUser->getCommentId());
		$this->assertTrue($this->commentUser->getCommentId() > 0);
		$this->assertIdentical($this->commentUser->getProfileId(),							$this->profile->getProfileId());
		$this->assertIdentical($this->commentUser->getCommentId(),							$this->comment->commentId);

	}

	// test deleting a CommentUser
	public function testDeleteCommentUser() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a CommentUser to post to mySQL
		$this->commentUser = new CommentUser($this->profile->getProfileId(), $this->comment->commentId);

		// third, insert the CommentUser to mySQL
		$this->commentUser->insert($this->mysqli);

		// fourth, verify the CommentUser was inserted
		$this->assertNotNull($this->commentUser->getProfileId());
		$this->assertTrue($this->commentUser->getProfileId() > 0);
		$this->assertNotNull($this->commentUser->getCommentId());
		$this->assertTrue($this->commentUser->getCommentId() > 0);

		// fifth, delete the commentUser
		$this->commentUser->delete($this->mysqli);
		$this->commentUser = null;

		// finally, try to get the userTeam and assert we didn't get a thing
		$hopefulUserCommentId = CommentUser::getCommentUserByProfileCommentId ($this->mysqli, $this->profile->getProfileId(), $this->comment->commentId);
		$this->assertNull($hopefulUserCommentId);
	}
	// test grabbing a userTeam from mySQL
	public function testGetCommentUserByProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentUser to post to mySQL
		$this->commentUser = new CommentUser($this->profile->getProfileId(), $this->comment->commentId);

		// third, insert the CommentUser to mySQL
		$this->commentUser->insert($this->mysqli);

		// fourth, get the commentUser using the static method
		$staticCommentUser = CommentUser::getCommentUserByProfileCommentId($this->mysqli, $this->commentUser->getProfileId(),
																								 $this->commentUser->getCommentId());

		// finally, compare the fields to upload
		$this->assertNotNull($staticCommentUser->getProfileId());
		$this->assertTrue($staticCommentUser->getProfileId() > 0);
		$this->assertNotNull($staticCommentUser->getCommentId());
		$this->assertTrue($staticCommentUser->getCommentId() > 0);
		$this->assertIdentical($staticCommentUser->getProfileId(),							$this->profile->getProfileId());
		$this->assertIdentical($staticCommentUser->getCommentId(),							$this->comment->commentId);
	}


}