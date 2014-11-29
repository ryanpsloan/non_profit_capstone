<?php

//Require the simpletest framework and the files this test relies on
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/team.php");
require_once("../php/cause.php");

//Require class under scrutiny
require_once("../php/teamcause.php");

//the TeamCause container for all the tests
class TeamCauseTest extends UnitTestCase
{

	//variable to hold the mySQL connection
	private $mysqli = null;

	//variable to hold the test database
	private $teamCause = null;

	//the "global" variables to create test data

	private $team = null;
	private $cause = null;

	public function setUp() {

		//connect to mySQL and add data
		$this->mysqli = MysqliConfiguration::getMysqli();

		$i = rand(0, 100000);
		$this->team = new Team(null, "Team DMC" . $i, "Help Dameon because he is behind");
		$this->team->insert($this->mysqli);

		$this->cause = new Cause(null, "Homeless coders", "no homes");
		$this->cause->insert($this->mysqli);
	}

	public function tearDown(){
		//delete the teamCause if we can
		if($this->teamCause !== null){
			$this->teamCause->delete($this->mysqli);
			$this->teamCause = null;
		}
		
		if($this->cause !== null){
			$this->cause->delete($this->mysqli);
			$this->cause = null;
		}
		
		if($this->team !== null){
			$this->team->delete($this->mysqli);
			$this->team = null;
		}
	}

	public function testInsertNewTeamCause() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a teamTeamCause to post to mySQL
		$this->teamCause = new TeamCause($this->team->getTeamId(), $this->cause->getCauseId());

		// third, insert the teamCause, to mySQL
		$this->teamCause->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->teamCause->teamId);
		$this->assertTrue($this->teamCause->teamId > 0);
		$this->assertNotNull($this->teamCause->causeId);
		$this->assertTrue($this->teamCause->causeId > 0);
		$this->assertIdentical($this->teamCause->teamId,							$this->team->getTeamId());
		$this->assertIdentical($this->teamCause->causeId,								$this->cause->getCauseId());

	}

	public function testDeleteTeamCause() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a teamCause to post to mySQL
		$this->teamCause = new TeamCause($this->team->getTeamId(), $this->cause->getCauseId());

		// third, insert the teamCause to mySQL
		$this->teamCause->insert($this->mysqli);

		// fourth, verify the teamCause was inserted
		$this->assertNotNull($this->teamCause->teamId);
		$this->assertTrue($this->teamCause->teamId > 0);
		$this->assertNotNull($this->teamCause->causeId);
		$this->assertTrue($this->teamCause->causeId > 0);

		// fifth, delete the teamCause
		$this->teamCause->delete($this->mysqli);
		$this->teamCause = null;

		// finally, try to get the teamCause and assert we didn't get a thing
		$hopefulTeamCauseId = TeamCause::getTeamCauseByTeamId($this->mysqli, $this->team->getTeamId(), $this->cause->getCauseId());
		$this->assertNull($hopefulTeamCauseId);
	}

	public function testGetTeamCauseByTeamId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a teamCause to post to mySQL
		$this->teamCause = new TeamCause($this->team->getTeamId(), $this->cause->getCauseId());

		// third, insert the teamCause to mySQL
		$this->teamCause->insert($this->mysqli);

		// fourth, get the teamCause using the static method
		$staticTeamCause = TeamCause::getTeamCauseByTeamId($this->mysqli, $this->teamCause->teamId,
			$this->teamCause->causeId);

		// finally, compare the fields to upload
		for($i = 0; $i < count($staticTeamCause); $i++) {
			$this->assertNotNull($staticTeamCause[$i]->teamId);
			$this->assertTrue($staticTeamCause[$i]->teamId > 0);
			$this->assertNotNull($staticTeamCause[$i]->causeId);
			$this->assertTrue($staticTeamCause[$i]->causeId > 0);
			$this->assertIdentical($staticTeamCause[$i]->teamId, $this->team->getTeamId());
		}
	}

	public function testGetTeamCauseByCauseId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a teamCause to post to mySQL
		$this->teamCause = new TeamCause($this->team->getTeamId(), $this->cause->getCauseId());

		// third, insert the teamCause to mySQL
		$this->teamCause->insert($this->mysqli);

		// fourth, get the teamCause using the static method
		$staticTeamCause = TeamCause::getTeamCauseByCauseId($this->mysqli, $this->teamCause->teamId,
			$this->teamCause->causeId);

		// finally, compare the fields to upload
		for($i = 0; $i < count($staticTeamCause); $i++) {
			$this->assertNotNull($staticTeamCause[$i]->teamId);
			$this->assertTrue($staticTeamCause[$i]->teamId > 0);
			$this->assertNotNull($staticTeamCause[$i]->causeId);
			$this->assertTrue($staticTeamCause[$i]->causeId > 0);
			$this->assertIdentical($staticTeamCause[$i]->causeId, $this->cause->getCauseId());
		}
	}

}