<?php
/**
 * Unit test for CommentEvent class
 * User: Martin
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/event.php");
require_once("../php/comment.php");

//then require class under scrutiny
require_once("../php/commentEvent.php");

//the CommentEventTest for all our testsclass CommentEventTest extends UnitTestCase {
class CommentEventTest extends UnitTestCase{

	//variable to hold the test database row
	private $mysqli = null;
	//variable to hold the test database row
	private $commentEvent = null;
	private $commentEvent1 = null;

	//the rest of the "global" variables used to create test data

	private $event = null;
	private $event1 = null;

	private $comment = null;
	private $comment1 = null;


	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp()
	{
		//connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

	//create some test classes to insert to mysqli to test

		$this->event = new Event(null,"Keep the Stemulus Center Clean", new DateTime(),"Albuquerque");
		$this->event->insert($this->mysqli);
		$this->event1 = new Event(null, "Keeping Our Women Protected", new DateTime(), "Old Town");
		$this->event1->insert($this->mysqli);

		$this->comment = new Comment(null, "stemulus is dirty from the weekend activity", new DateTime());
		$this->comment->insert($this->mysqli);
		$this->comment1 = new Comment(null, "Lets end domestic violence", new DateTime());
		$this->comment1->insert($this->mysqli);

	}
	//Teardown (), a method to delete the test record and disconnect from mySQL
	public function tearDown(){
		// delete the user if we can
		if($this->commentEvent !== null) {
			$this->commentEvent->delete($this->mysqli);
			$this->commentEvent = null;
		}

		if($this->commentEvent1 !== null) {
			$this->commentEvent1->delete($this->mysqli);
			$this->commentEvent1 = null;
		}

		if($this->comment !== null) {
			$this->comment->delete($this->mysqli);
			$this->comment = null;
		}

		if($this->comment1 !== null) {
			$this->comment1->delete($this->mysqli);
			$this->comment1 = null;
		}

		if($this->event !== null) {
			$this->event->delete($this->mysqli);
			$this->event = null;
		}

		if($this->event1 !== null) {
			$this->event1->delete($this->mysqli);
			$this->event1 = null;
		}
	}

	// test creating a new event Id and comment Id and inserting it to mySQL
	public function testInsertNewCommentEvent() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentEvent to post to mySQL
		$this->commentEvent = new CommentEvent($this->event->eventId, $this->comment->commentId);

		// third, insert the commentEvent, to mySQL
		$this->commentEvent->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->commentEvent->eventId);
		$this->assertTrue($this->commentEvent->eventId > 0);
		$this->assertNotNull($this->commentEvent->commentId);
		$this->assertTrue($this->commentEvent->commentId > 0);
		$this->assertIdentical($this->commentEvent->eventId,								$this->event->eventId);
		$this->assertIdentical($this->commentEvent->commentId,							$this->comment->commentId);

	}

	// test deleting a CommentEvent
	public function testDeleteCommentEvent() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a CommentEvent to post to mySQL
		$this->commentEvent = new CommentEvent($this->event->eventId, $this->comment->commentId);

		// third, insert the CommentEvent to mySQL
		$this->commentEvent->insert($this->mysqli);

		// fourth, verify the CommentEvent was inserted
		$this->assertNotNull($this->commentEvent->eventId);
		$this->assertTrue($this->commentEvent->eventId > 0);
		$this->assertNotNull($this->commentEvent->commentId);
		$this->assertTrue($this->commentEvent->commentId > 0);

		// fifth, delete the commentEvent
		$this->commentEvent->delete($this->mysqli);
		$this->commentEvent = null;

		// finally, try to get the commentEvent and assert we didn't get a thing
		$hopefulCommentEventId = CommentEvent::getCommentEventByEventCommentId($this->mysqli, $this->event->eventId, $this->comment->commentId);
		$this->assertNull($hopefulCommentEventId);
	}

	// test grabbing a commentEvent from mySQL
	public function testGetCommentEventByEventCommentId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentEvent to post to mySQL
		$this->commentEvent = new CommentEvent($this->event->eventId, $this->comment->commentId);

		// third, insert the CommentEvent to mySQL
		$this->commentEvent->insert($this->mysqli);

		// fourth, get the commentEvent using the static method
		$staticCommentEvent = CommentEvent::getCommentEventByEventCommentId($this->mysqli, $this->commentEvent->eventId,
			$this->commentEvent->commentId);

		// finally, compare the fields to upload
		$this->assertNotNull($staticCommentEvent->eventId);
		$this->assertTrue($staticCommentEvent->eventId > 0);
		$this->assertNotNull($staticCommentEvent->commentId);
		$this->assertTrue($staticCommentEvent->commentId > 0);
		$this->assertIdentical($staticCommentEvent->eventId,									$this->event->eventId);
		$this->assertIdentical($staticCommentEvent->commentId,								$this->comment->commentId);
	}

	public function testGetCommentEventByEventId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentEvent to post to mySQL
		$this->commentEvent = new CommentEvent($this->event->eventId, $this->comment->commentId);
		$this->commentEvent1 = new CommentEvent($this->event1->eventId, $this->comment1->commentId);

		// third, insert the commentEvent to mySQL
		$this->commentEvent->insert($this->mysqli);
		$this->commentEvent1->insert($this->mysqli);

		// fourth, get the commentEvent using the static method
		$staticCommentEvent = CommentEvent::getCommentEventByEventId($this->mysqli, $this->commentEvent->eventId);

		// finally, compare the fields
		for($i = 0; $i < count($staticCommentEvent); $i++){
			$this->assertNotNull($staticCommentEvent[$i]->eventId);
			$this->assertTrue($staticCommentEvent[$i]->eventId > 0);
			$this->assertNotNull($staticCommentEvent[$i]->commentId);
			$this->assertTrue($staticCommentEvent[$i]->commentId > 0);
			$this->assertIdentical($staticCommentEvent[$i]->eventId,									$this->event->eventId);
		}

	}

	public function testGetCommentEventByCommentId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentEvent to post to mySQL
		$this->commentEvent = new CommentEvent($this->event->eventId, $this->comment->commentId);
		$this->commentEvent1 = new CommentEvent($this->event1->eventId, $this->comment1->commentId);

		// third, insert the commentEvent to mySQL
		$this->commentEvent->insert($this->mysqli);
		$this->commentEvent1->insert($this->mysqli);

		// fourth, get the commentEvent using the static method
		$staticCommentEvent = CommentEvent::getCommentEventByCommentId($this->mysqli, $this->commentEvent->commentId);

		// finally, compare the fields
		for($i = 0; $i < count($staticCommentEvent); $i++){
			$this->assertNotNull($staticCommentEvent[$i]->eventId);
			$this->assertTrue($staticCommentEvent[$i]->eventId > 0);
			$this->assertNotNull($staticCommentEvent[$i]->commentId);
			$this->assertTrue($staticCommentEvent[$i]->commentId > 0);
			$this->assertIdentical($staticCommentEvent[$i]->commentId,									$this->comment->commentId);
		}

	}

}