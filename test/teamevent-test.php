<?php
//@author Dameon Smith

require_once("/usr/lib/php5/simpletest/autorun.php");
//then require the class under scrutiny
require_once("/etc/apache2/capstone-mysql/helpabq.php");
// ../ means go up one directory
require_once("../php/team.php");
require_once("../php/event.php");
require_once("../php/teamevent.php");

class TeamEventTest extends UnitTestCase {
	//variable to hold the mySQL connection
	private $mysqli = null;
	//variable to hold the test database row
	private $teamEvent  = null;
	private $event = null;
	private $team = null;
	private $event1 = null;
	private $team1 = null;
	//a few global variables for creating test data
	private $TEAMID = null;
	private $EVENTID = null;
	private $TEAMSTATUS = 1;
	private $COMMENTPERMISSION = 1;
	private $BANSTATUS	= 1;

	public function setUp(){
		$this->mysqli = MysqliConfiguration::getMysqli();

		$i = rand(0, 100000);
		$this->team = new Team(null, "Team DMC" . $i, "Help Dameon because he is behind");
		$this->team->insert($this->mysqli);
		$this->team1 = new Team(null, "Team somethin" . $i, "Helpdafds Dameon because he is behind");
		$this->team1->insert($this->mysqli);

		$this->event = new Event(null, "EventTitle", "1995-12-12 12:12:12", "Last one down");
		$this->event->insert($this->mysqli);
		$this->event1 = new Event(null, "EventTitle2", "1995-12-12 12:12:12", "Last one down");
		$this->event1->insert($this->mysqli);
	}

	public function tearDown(){
		// delete the teamEvent if we can
		if($this->teamEvent !== null) {
			$this->teamEvent->delete($this->mysqli);
			$this->teamEvent = null;
		}

		if($this->event !== null) {
			$this->event->delete($this->mysqli);
			$this->event = null;
		}
		if($this->team !== null) {
			$this->team->delete($this->mysqli);
			$this->team = null;
		}

	}

	public function testInsertNewTeamEvent() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a TeamEvent to post to mySQL
		$this->teamEvent = new TeamEvent($this->team->getTeamId(), $this->event->__get("eventId"), $this->TEAMSTATUS,
													$this->COMMENTPERMISSION, $this->BANSTATUS);

		// third, insert the TeamEvent, to mySQL
		$this->teamEvent->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->teamEvent->teamId);
		$this->assertTrue($this->teamEvent->teamId > 0);
		$this->assertNotNull($this->teamEvent->eventId);
		$this->assertTrue($this->teamEvent->eventId > 0);
		$this->assertIdentical($this->teamEvent->teamId,									$this->team->getTeamId());
		$this->assertIdentical($this->teamEvent->eventId,									$this->event->eventId);
		$this->assertIdentical($this->teamEvent->teamStatus,								$this->TEAMSTATUS);
		$this->assertIdentical($this->teamEvent->commentPermission,						$this->COMMENTPERMISSION);
		$this->assertIdentical($this->teamEvent->banStatus,								$this->BANSTATUS);

	}

	public function testUpdateTeamEvent() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a team to post to mySQL
		$this->teamEvent = new TeamEvent($this->team->getTeamId(), $this->event->eventId, $this->TEAMSTATUS,
			$this->COMMENTPERMISSION, $this->BANSTATUS);

		// insert the team to mySQL
		$this->teamEvent->insert($this->mysqli);

		// fourth, update the team and post the changes to mySQL
		$newTeamStatus = 5;
		$this->teamEvent->setTeamStatus($newTeamStatus);
		$this->teamEvent->update($this->mysqli);


		// update the team and post the changes to mySQL
		$this->assertNotNull($this->team->getTeamId());
		$this->assertTrue($this->team->getTeamId() > 0);
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue($this->event->eventId > 0);




		//fifth delete the article
		$this->teamEvent->delete($this->mysqli);
		$this->teamEvent = null;

		//finally try to get the team and assert we didn't get a thing
		$hopefulTeamEvent = TeamEvent::getTeamEventByTeamId($this->mysqli, $this->team->getTeamId());
		$this->assertNull($hopefulTeamEvent);
	}

	public function testDeleteNewTeamEvent(){
		//first verify connection to mySQL
		$this->assertNotNull($this->mysqli);

		//second create a event to post to mySQL
		$this->teamEvent = new TeamEvent($this->team->getTeamId(), $this->event->__get("eventId"), $this->TEAMSTATUS,
													$this->COMMENTPERMISSION, $this->BANSTATUS);

		// third, insert the event to mySQL
		$this->teamEvent->insert($this->mysqli);

		// fourth verify the Event was inserted
		$this->assertNotNull($this->teamEvent->teamId);
		$this->assertTrue ($this->teamEvent->eventId);
		$this->EVENTID = $this->event->eventId;

		//fifth, delete the article
		$this->teamEvent->delete($this->mysqli);
		$this->teamEvent = null;

		//finally, try to get the event and assert we didn't get a thing
		$hopefulTeamEvent = TeamEvent::getTeamEventByEventId($this->mysqli, $this->EVENTID);
		$this->assertNull($hopefulTeamEvent);

	}

	public function testGetTeamEventByEventId(){
		// first verify mySQL connection
		$this->assertNotNull($this->mysqli);
		//second create an event to post to mySQL
		$this->teamEvent = new TeamEvent($this->team->getTeamId(), $this->event->eventId, $this->TEAMSTATUS,
													$this->COMMENTPERMISSION, $this->BANSTATUS);
		// third insert event to mySQL
		$this->teamEvent->insert($this->mysqli);

		//fourth, get the event using the static method
		$staticTeamEvent = TeamEvent::getTeamEventByEventId($this->mysqli, $this->event->eventId);
		// finally compare the fields
		for($i = 0; $i < count($staticTeamEvent); $i++) {
			$this->assertNotNull($staticTeamEvent[$i]->eventId);
			$this->assertTrue($staticTeamEvent[$i]->eventId > 0);
			$this->assertIdentical($staticTeamEvent[$i]->eventId, $this->event->eventId);
		}
	}

	public function testGetTeamEventByTeamId() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a team to post to mySQL
		$this->teamEvent = new TeamEvent($this->team->getTeamId(), $this->event->eventId, $this->TEAMSTATUS,
													$this->COMMENTPERMISSION, $this->BANSTATUS);

		// third, insert the team to mySQL
		$this->teamEvent->insert($this->mysqli);

		// fourth, get the team using the static method
		$staticTeamEvent = TeamEvent::getTeamEventByTeamId($this->mysqli, $this->team->getTeamId());

		// finally, compare the fields
		for($i = 0; $i < count($staticTeamEvent); $i++) {
			$this->assertNotNull($staticTeamEvent[$i]->teamId);
			$this->assertTrue($staticTeamEvent[$i]->teamId > 0);
			$this->assertIdentical($staticTeamEvent[$i]->teamId, $this->team->getTeamId());
		}
	}
}