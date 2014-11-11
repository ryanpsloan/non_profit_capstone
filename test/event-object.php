<?php
//first require this SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
//then require the class under scrutiny
// ../ means go up one directory
require_once("../php/event.php");
//the articleTest is a container for all our tests
class eventTest extends UnitTestCase {
	//variable to hold the mySQL connection
	private $mysql = null;
	//variable to hold the test database row
	private $event  = null;
	//a few global variables for creating test data
	private $EVENTID = 1;
	private $EVENTTITLE = "This is the title for the event!";
	private $EVENTDATE = "1995-12-12 14:12:23";
	private $EVENTLOCATION = "At the zoo.";
	//setUp() is a method that is run before each test
	//here, we use it to connect to mySQL and to calculate salt, authentication token and hash if we need it
	public function setUp() {
		// connect to mySQL
		mysqli_report (MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "helpabq-dba", "deepdive","helpabq-dba");

	}
	// tearDown() is a method that is run after each test
	// here, we use it to delete the test record and disconnect from mySQL
	public function tearDown() {
		//delete the article if we can
		if($this->event !== null) {
			$this->event->delete($this->mysqli);
			$this->event = null;
		}
		//disconnect from mySQL
		if($this->mysqli !== null) {
			$this->mysqli->close();
		}
	}
	//test creating a new Article and inserting it into SQL
	/**
	 *
	 */
	public function testInsertNewUser() {
		//first verify mySQL connected OK;
		$this->assertNotNull($this->mysqli);
		//second, create an Article to post to mySQL
		$this->event = new Event(null, $this->EVENTTITLE, $this->EVENTDATE, $this->EVENTLOCATION);
		//third, insert the article into mySQL
		$this->event->insert($this->mysqli);
		//finally, compare the fields
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue($this->event->eventId > 0);
		$this->assertIdentical($this->event->eventTitle, $this->EVENTTITLE);
		$this->assertIdentical($this->event->eventDate, $this->EVENTDATE);
		$this->assertIdentical($this->event->eventLocation, $this->EVENTLOCATION);
	}
	//test updating an Article in mySQL
	public function testInsertNewArticle(){
		//first verify mySQL connection
		$this->assertNotNull($this->mysqli);
		// second, create a user to post to mySQL
		$this->event = new Event(null, $this->EVENTTITLE, $this->EVENTDATE, $this->EVENTLOCATION);
		// third, insert the user into mySQL
		$this->event->insert($this->mysqli);
		// finally compare the fields
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue($this->event->eventId > 0);
		$this->assertIdentical($this->event->eventTitle,			$this->EVENTTITLE);
		$this->assertIdentical($this->event->eventDate,					$this->EVENTDATE);
		$this->assertIdentical($this->event->eventLocation,				$this->EVENTLOCATION);
		$this->event->delete($this->mysqli);
	}
	//test updating an Article in mySQL
	public function testUpdateEvent(){
		// Verify connection
		$this->assertNotNull($this->mysqli);
		// second create a user to post to mySQL
		$this->event = new Event(null, $this->EVENTTITLE, $this->EVENTDATE, $this->EVENTLOCATION);
		// third, insert into mySQL
		$this->event->insert($this->mysqli);
		//fourth verify user was inserted
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue($this->event->eventId > 0);
		//fifth delete the article
		$this->event->delete($this->mysqli);
		$this->event = null;
		//finally try to get the user and assert we didn't get a thing
		$hopefulEvent = Event::getEventByEventId($this->mysqli, $this->EVENTID);
		$this->assertNull($hopefulEvent);
	}
	// test deleting an Article
	public function testDeleteEvent(){
		//first verify connection to mySQL
		$this->assertNotNull($this->mysqli);
		//second create a user to post to mySQL
		$this->event = new Event(null, $this->EVENTTITLE, $this->EVENTDATE, $this->EVENTLOCATION);
		// third, insert the user to mySQL
		$this->event->insert($this->mysqli);
		// fourth verify the Article was inserted
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue ($this->event->eventId);
		//fifth, delete the article
		$this->event->delete($this->mysqli);
		$this->event = null;
		//finally, try to get the user and assert we didn't get a thing
		$hopefulEvent = Event::getEventByEventId($this->mysqli, $this->EVENTID);
		$this->assertNull($hopefulEvent);
	}
	// test grabbing a User from mySQL
	public function testGetEventByEventId(){
		// first verify mySQL connection
		$this->assertNotNull($this->mysqli);
		//second create a user to post to mySQL
		$this->event = new Event(null, $this->EVENTTITLE, $this->EVENTDATE, $this->EVENTLOCATION);
		// third insert user to mySQL
		$this->event->insert($this->mysqli);
		//fourth, get the user using the static method
		$staticEvent = Event::getEventbyEventId($this->mysqli, $this->SOURCE);
		// finally compare the fields
		$dateString = $staticEvent->eventDate->format("Y-m-d H:i:s");
		$this->assertNotNull($staticArticle->eventId);
		$this->assertTrue($staticArticle->eventId > 0);
		$this->assertIdentical($staticArticle->profileId,			$this->EVENTTITLE);
		$this->assertIdentical($dateString,								$this->EVENTDATE);
		$this->assertIdentical($staticArticle->source,				$this->EVENTLOCATION);
	}
}
?>