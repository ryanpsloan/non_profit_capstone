<?php
/**
 * Unit test for CommentTeam class
 * User: Martin
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/team.php");
require_once("../php/comment.php");

//then require class under scrutiny
require_once("../php/commentTeam.php");

//the CommentTeamTest for all our testsclass UserTeamTest extends UnitTestCase {
class CommentTeamTest extends UnitTestCase{

	//variable to hold the test database row
	private $mysqli = null;
	//variable to hold the test database row
	private $commentTeam = null;

	//the rest of the "global" variables used to create test data

	private $team = null;
	private $comment = null;


	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp()
	{
		//connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();


		$this->team = new Team(null, "Team DMC", "Help Dameon because he is behind");
		$this->team->insert($this->mysqli);


		$this->comment = new Comment(null, "waiting on unit test", new DateTime());
		$this->comment->insert($this->mysqli);
	}

	//Teardown (), a method to delete the test record and disconnect from mySQL
	public function tearDown(){
		// delete the user if we can
		if($this->commentTeam !== null) {
			$this->commentTeam->delete($this->mysqli);
			$this->commentTeam = null;
		}

		if($this->comment !== null) {
			$this->comment->delete($this->mysqli);
			$this->comment = null;
		}

		if($this->team !== null) {
			$this->team->delete($this->mysqli);
			$this->team = null;
		}
	}

	// test creating a new team Id and comment Id and inserting it to mySQL
	public function testInsertNewCommentTeam() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);


		// second, create a commentTeam to post to mySQL
		$this->commentTeam = new CommentTeam($this->team->getTeamId(), $this->comment->commentId);

		// third, insert the commentTeam, to mySQL
		$this->commentTeam->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->commentTeam->teamId);
		$this->assertTrue($this->commentTeam->teamId > 0);
		$this->assertNotNull($this->commentTeam->commentId);
		$this->assertTrue($this->commentTeam->commentId > 0);
		$this->assertIdentical($this->commentTeam->teamId,										$this->team->getTeamId());
		$this->assertIdentical($this->commentTeam->commentId,									$this->comment->commentId);

	}

	// test deleting a CommentTeam
	public function testDeleteCommentTeam() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a CommentTeam to post to mySQL
		$this->commentTeam = new CommentTeam($this->team->getTeamId(), $this->comment->commentId);

		// third, insert the CommentTeam to mySQL
		$this->commentTeam->insert($this->mysqli);

		// fourth, verify the CommentTeam was inserted
		$this->assertNotNull($this->commentTeam->teamId);
		$this->assertTrue($this->commentTeam->teamId > 0);
		$this->assertNotNull($this->commentTeam->commentId);
		$this->assertTrue($this->commentTeam->commentId > 0);

		// fifth, delete the commentTeam
		$this->commentTeam->delete($this->mysqli);
		$this->commentTeam = null;

		// finally, try to get the userTeam and assert we didn't get a thing
		$hopefulUserCommentId = CommentTeam::getCommentTeamByTeamCommentId ($this->mysqli, $this->team->getTeamId(), $this->comment->commentId);
		$this->assertNull($hopefulUserCommentId);
	}
	// test grabbing a userTeam from mySQL
	public function testGetCommentTeamByTeamId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentTeam to post to mySQL
		$this->commentTeam = new CommentTeam($this->team->getTeamId(), $this->comment->commentId);

		// third, insert the CommentTeam to mySQL
		$this->commentTeam->insert($this->mysqli);

		// fourth, get the commentTeam using the static method
		$staticCommentTeam = CommentTeam::getCommentTeamByTeamCommentId($this->mysqli, $this->commentTeam->teamId,
			$this->commentTeam->commentId);

		// finally, compare the fields to upload
		$this->assertNotNull($staticCommentTeam->teamId);
		$this->assertTrue($staticCommentTeam->teamId > 0);
		$this->assertNotNull($staticCommentTeam->commentId);
		$this->assertTrue($staticCommentTeam->commentId > 0);
		$this->assertIdentical($staticCommentTeam->teamId,										$this->team->getTeamId());
		$this->assertIdentical($staticCommentTeam->commentId,									$this->comment->commentId);
	}


}