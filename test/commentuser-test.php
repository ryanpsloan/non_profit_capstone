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
	private $commentUser1 = null;

	//the rest of the "global" variables used to create test data

	private $user = null;
	private $user1 = null;
	private $profile = null;
	private $profile1 = null;
	private $comment = null;
	private $comment1 = null;


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
		$this->user = new User(null,"igotthismaybe".$i, "myhomie".$i."@yahoo.com",$passwordHash,$salt,$authToken,2);
		$this->user->insert($this->mysqli);
		$this->user1 = new User(null,"mrnoodles".$i, "mrnoodles".$i."@sesamestreet.com",$passwordHash,$salt,$authToken,1);
		$this->user1->insert($this->mysqli);


		$this->profile = new Profile(null, $this->user->getUserId(),"Mr.","Edward", "P", "Stressed", "I have the largest signature on the
				Declaration of Independence", "PM of Britain", "1600 Pennsylvania Ave", "SW", "Washington", "DC", "10500");
		$this->profile->insert($this->mysqli);

		$this->profile1 = new Profile(null, $this->user1->getUserId(),"Mr.","Jim", "N", "Noodles", "Can you tell how to get to Sesame Street",
												"Elmo", "2461 Sesame Street Way", "SW", "MakeBelieve", "CA", "20501");
		$this->profile1->insert($this->mysqli);


		$this->comment = new Comment(null, "waiting on unit test", new DateTime());
		$this->comment->insert($this->mysqli);
		$this->comment1 = new Comment(null, "I hope unit test passed already", new DateTime());
		$this->comment1->insert($this->mysqli);
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

		if($this->comment1 !== null) {
			$this->comment1->delete($this->mysqli);
			$this->comment1 = null;
		}

		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}

		if($this->profile1 !== null) {
			$this->profile1->delete($this->mysqli);
			$this->profile1 = null;
		}

		if($this->user !== null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}

		if($this->user1 !== null) {
			$this->user1->delete($this->mysqli);
			$this->user1 = null;
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

	// test grabbing a commentUser from mySQL
	public function testGetCommentUserByProfileCommentId() {

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

	public function testGetCommentUserByProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentUser to post to mySQL
		$this->commentUser = new CommentUser($this->profile->getProfileId(), $this->comment->commentId);
		$this->commentUser1 = new CommentUser($this->profile->getProfileId(), $this->comment1->commentId);

		// third, insert the commentUser to mySQL
		$this->commentUser->insert($this->mysqli);
		$this->commentUser1->insert($this->mysqli);

		// fourth, get the commentUser using the static method
		$staticCommentUser = CommentUser::getCommentUserByProfileId ($this->mysqli, $this->commentUser->getProfileId());

		// finally, compare the fields
		for($i = 0; $i < count($staticCommentUser); $i++){
			$this->assertNotNull($staticCommentUser[$i]->getProfileId());
			$this->assertTrue($staticCommentUser[$i]->getProfileId() > 0);
			$this->assertNotNull($staticCommentUser[$i]->getCommentId());
			$this->assertTrue($staticCommentUser[$i]->getCommentId() > 0);
			$this->assertIdentical($staticCommentUser[$i]->getProfileId(),			 $this->commentUser->getProfileId());
		}
		//teardown for commentUser1
		$this->commentUser1->delete($this->mysqli);
	}

	public function testGetCommentUserByCommentId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentUser to post to mySQL
		$this->commentUser = new CommentUser($this->profile->getProfileId(), $this->comment->commentId);
		$this->commentUser1 = new CommentUser($this->profile1->getProfileId(), $this->comment1->commentId);

		// third, insert the commentUser to mySQL
		$this->commentUser->insert($this->mysqli);
		$this->commentUser1->insert($this->mysqli);

		// fourth, get the commentTeam using the static method
		$staticCommentUser = CommentUser::getCommentUserByCommentId($this->mysqli, $this->commentUser->getCommentId());

		// finally, compare the fields
		for($i = 0; $i < count($staticCommentUser); $i++){
			$this->assertNotNull($staticCommentUser[$i]->getProfileId());
			$this->assertTrue($staticCommentUser[$i]->getProfileId() > 0);
			$this->assertNotNull($staticCommentUser[$i]->getCommentId());
			$this->assertTrue($staticCommentUser[$i]->getCommentId() > 0);
			$this->assertIdentical($staticCommentUser[$i]->getCommentId(),			 $this->commentUser->getCommentId());
		}
		//teardown for commentUser1
		$this->commentUser1->delete($this->mysqli);
	}

}