<?php
//first require this SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
//then require the class under scrutiny
// ../ means go up one directory
require_once("../php/event.php");
//the articleTest is a container for all our tests
class eventTest extends UnitTestCase {
	//variable to hold the mySQL connection
	private $mysqli = null;
	//variable to hold the test database row
	private $event  = null;
	//a few global variables for creating test data
	private $EVENTID = null;
	private $EVENTTITLE = "This is the title for the event!";
	private $EVENTDATE = null;
	private $EVENTLOCATION = "At the zoo.";
	//setUp() is a method that is run before each test
	//here, we use it to connect to mySQL and to calculate salt, authentication token and hash if we need it
	public function setUp() {
		// connect to mySQL
		mysqli_report (MYSQLI_REPORT_STRICT);
		$this->mysqli = new mysqli("localhost", "helpabq", "lillymiragefenceirsfind","helpabq");
		$this->EVENTDATE = DateTime::createFromFormat("Y-m-d H:i:s", "1995-12-12 12:12:12");
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
	//test creating a new Event and inserting it into SQL

	public function testInsertNewEvent() {
		//first verify mySQL connected OK;
		$this->assertNotNull($this->mysqli);
		//second, create an Event to post to mySQL
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
	//test updating an Event in mySQL
	public function testUpdateEvent(){
		// Verify connection
		$this->assertNotNull($this->mysqli);
		// second create a event to post to mySQL
		$this->event = new Event(null, $this->EVENTTITLE, $this->EVENTDATE, $this->EVENTLOCATION);
		// third, insert into mySQL
		$this->event->insert($this->mysqli);
		//fourth verify event was inserted
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue($this->event->eventId > 0);
		//fifth delete the article
		$this->event->delete($this->mysqli);
		$this->event = null;
		//finally try to get the event and assert we didn't get a thing
		$hopefulEvent = Event::getEventByEventId($this->mysqli, $this->EVENTID);
		$this->assertNull($hopefulEvent);
	}
	// test deleting an Event
	public function testDeleteEvent(){
		//first verify connection to mySQL
		$this->assertNotNull($this->mysqli);

		//second create a event to post to mySQL
		$this->event = new Event(null, $this->EVENTTITLE, $this->EVENTDATE, $this->EVENTLOCATION);

		// third, insert the event to mySQL
		$this->event->insert($this->mysqli);

		// fourth verify the Event was inserted
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue ($this->event->eventId);

		//fifth, delete the article
		$this->event->delete($this->mysqli);
		$this->event = null;

		//finally, try to get the event and assert we didn't get a thing
		$hopefulEvent = Event::getEventByEventId($this->mysqli, $this->EVENTID);
		$this->assertNull($hopefulEvent);
	}

	// test grabbing an event from mySQL
	public function testGetEventByEventId(){
		// first verify mySQL connection
		$this->assertNotNull($this->mysqli);
		//second create an event to post to mySQL
		$this->event = new Event(null, $this->EVENTTITLE, $this->EVENTDATE, $this->EVENTLOCATION);
		// third insert event to mySQL
		$this->event->insert($this->mysqli);

		//fourth, get the event using the static method
		$staticEvent = Event::getEventByEventId($this->mysqli, $this->event->eventId);
		// finally compare the fields
		$this->assertNotNull($staticEvent->eventId);
		$this->assertTrue($staticEvent->eventId > 0);
		$this->assertIdentical($staticEvent->eventId, 					$this->event->eventId);
		$this->assertIdentical($staticEvent->eventTitle,					$this->EVENTTITLE);
		$this->assertIdentical($staticEvent->eventDate,						$this->EVENTDATE);
		$this->assertIdentical($staticEvent->eventLocation,				$this->EVENTLOCATION);
	}
}
?>