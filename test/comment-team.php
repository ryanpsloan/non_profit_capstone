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
	private $commentTeam1 = null;

	//the rest of the "global" variables used to create test data

	private $team = null;
	private $team1 =null;
	private $comment = null;
	private $comment1 = null;

	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp()
	{
		//connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();


		$this->team = new Team(null, "Team DMC", "Help Dameon because he is behind");
		$this->team->insert($this->mysqli);
		$this->team1 = new Team(null, "Vato Locos", "You cry we die shut up");
		$this->team1->insert($this->mysqli);

		$this->comment = new Comment(null, "waiting on unit test", new DateTime());
		$this->comment->insert($this->mysqli);
		$this->comment1 = new Comment(null, "La Onda Forever!!", new DateTime());
		$this->comment1->insert($this->mysqli);
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

		if($this->comment1 !== null) {
			$this->comment1->delete($this->mysqli);
			$this->comment1 = null;
		}

		if($this->team !== null) {
			$this->team->delete($this->mysqli);
			$this->team = null;
		}

		if($this->team1 !== null) {
			$this->team1->delete($this->mysqli);
			$this->team1 = null;
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
	public function testGetCommentTeamByCommentTeamId() {

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

	public function testGetCommentTeamByTeamId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentTeam to post to mySQL
		$this->commentTeam = new CommentTeam($this->team->getTeamId(), $this->comment->commentId);
		$this->commentTeam1 = new CommentTeam($this->team1->getTeamId(), $this->comment1->commentId);

		// third, insert the commentUser to mySQL
		$this->commentTeam->insert($this->mysqli);
		$this->commentTeam1->insert($this->mysqli);

		// fourth, get the commentTeam using the static method
		$staticCommentTeam = CommentTeam::getCommentTeamByTeamId($this->mysqli, $this->commentTeam->teamId);

		// finally, compare the fields
		for($i = 0; $i < count($staticCommentTeam); $i++){
			$this->assertNotNull($staticCommentTeam[$i]->teamId);
			$this->assertTrue($staticCommentTeam[$i]->teamId > 0);
			$this->assertNotNull($staticCommentTeam[$i]->commentId);
			$this->assertTrue($staticCommentTeam[$i]->commentId > 0);
			$this->assertIdentical($staticCommentTeam[$i]->teamId,			 $this->team->getTeamId());
		}
		//teardown for commentTeam1
		$this->commentTeam1->delete($this->mysqli);
	}

	public function testGetCommentTeamByCommentId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentTeam to post to mySQL
		$this->commentTeam = new CommentTeam($this->team->getTeamId(), $this->comment->commentId);
		$this->commentTeam1 = new CommentTeam($this->team1->getTeamId(), $this->comment1->commentId);

		// third, insert the commentTeam to mySQL
		$this->commentTeam->insert($this->mysqli);
		$this->commentTeam1->insert($this->mysqli);

		// fourth, get the commentTeam using the static method
		$staticCommentTeam = CommentTeam::getCommentTeamByCommentId($this->mysqli, $this->commentTeam->commentId);

		// finally, compare the fields
		for($i = 0; $i < count($staticCommentTeam); $i++){
			$this->assertNotNull($staticCommentTeam[$i]->teamId);
			$this->assertTrue($staticCommentTeam[$i]->teamId > 0);
			$this->assertNotNull($staticCommentTeam[$i]->commentId);
			$this->assertTrue($staticCommentTeam[$i]->commentId > 0);
			$this->assertIdentical($staticCommentTeam[$i]->commentId,			 $this->comment->commentId);
		}
		//teardown for commentUser1
		$this->commentTeam1->delete($this->mysqli);
	}


}