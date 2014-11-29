<?php
/**
 * Unit test for comment
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */


require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/comment.php");

class CommentTest extends UnitTestCase {
	private $mysqli = null;

	private $comment = null;

	private $COMMENTID = null;

	private $COMMENTTEXT = "Hello this is a comment";

	private $COMMENTDATE = null;

	public function setUp() {
		// connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

		$this->COMMENTDATE = DateTime::createFromFormat("Y-m-d H:i:s", "1995-12-12 12:12:12");
	}

	public function tearDown(){
		if($this->comment !== null){
			$this->comment->delete($this->mysqli);
			$this->comment = null;
		}
	}

	public function testInsertNewComment() {
		//first verify mySQL connected OK;
		$this->assertNotNull($this->mysqli);
		//second, create an Event to post to mySQL
		$this->comment = new Comment(null, $this->COMMENTTEXT, $this->COMMENTDATE);
		//third, insert the article into mySQL
		$this->comment->insert($this->mysqli);
		//finally, compare the fields
		$this->assertNotNull($this->comment->commentId);
		$this->assertTrue($this->comment->commentId > 0);
		$this->assertIdentical($this->comment->commentText, $this->COMMENTTEXT);
		$this->assertIdentical($this->comment->commentDate, $this->COMMENTDATE);
	}


	public function testUpdateComment(){
		// Verify connection
		$this->assertNotNull($this->mysqli);
		// second create a event to post to mySQL
		$this->comment= new Comment(null, $this->COMMENTTEXT, $this->COMMENTDATE);
		// third, insert into mySQL

		$this->comment->insert($this->mysqli);
		//fourth verify event was inserted
		$this->assertNotNull($this->comment->commentId);
		$this->assertTrue($this->comment->commentId > 0);
		$this->COMMENTID = $this->comment->commentId;
		//fifth delete the article
		$this->comment->delete($this->mysqli);
		$this->comment = null;
		//finally try to get the event and assert we didn't get a thing
		$hopefulComment = Comment::getCommentByCommentId($this->mysqli, $this->COMMENTID);
		$this->assertNull($hopefulComment);

		$hopefulCommentTwo = Comment::getCommentByCommentDate($this->mysqli, $this->COMMENTDATE);
		$this->assertNull($hopefulCommentTwo);
	}

	// test deleting an Event
	public function testDeleteComment(){
		//first verify connection to mySQL
		$this->assertNotNull($this->mysqli);

		//second create a event to post to mySQL
		$this->comment = new Comment(null, $this->COMMENTTEXT, $this->COMMENTDATE);

		// third, insert the event to mySQL
		$this->comment->insert($this->mysqli);

		// fourth verify the Event was inserted
		$this->assertNotNull($this->comment->commentId);
		$this->assertTrue ($this->comment->commentId);
		$this->COMMENTID = $this->comment->commentId;

		//fifth, delete the article
		$this->comment->delete($this->mysqli);
		$this->comment = null;

		//finally, try to get the event and assert we didn't get a thing
		$hopefulComment = Comment::getCommentByCommentId($this->mysqli, $this->COMMENTID);
		$this->assertNull($hopefulComment);
	}

}
