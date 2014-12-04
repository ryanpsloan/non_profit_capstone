<?php

// Get the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");

// then require the class under scrutiny
require_once("../php/team.php");

// the teamTest is a container for all our tests
class TeamTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row
	private $team   = null;
	private $team1 = null;

	// a few "global" variables for creating test data
	private $TEAMID		= null;
	private $TEAMNAME   	= "Run DMC";
	private $TEAMCAUSE   = "homeless coders";

	// setUp() is a method that is run before each test
	// here, we use it to connect to mySQL and to calculate the salt, hash, and authenticationToken
	public function setUp() {

		// connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();
	}

	// tearDown() is a method that is run after each test

	public function tearDown() {
		// delete the team if we can
		if($this->team !== null) {
			$this->team->delete($this->mysqli);
			$this->team = null;
		}
	}
	// test creating a new team and inserting it to mySQL
	public function testInsertNewTeam() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a team to post to mySQL
		$this->team = new team(null, $this->TEAMNAME, $this->TEAMCAUSE);

		// third, insert the team to mySQL
		$this->team->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->team->getTeamId());
		$this->assertTrue($this->team->getTeamId() > 0);
		$this->assertIdentical($this->team->getTeamName(),  			$this->TEAMNAME);
		$this->assertIdentical($this->team->getTeamCause(),    		$this->TEAMCAUSE);
	}

	// test updating a team in mySQL
	public function testUpdateTeam() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a team to post to mySQL
		$this->team = new Team(null, $this->TEAMNAME, $this->TEAMCAUSE);

		// insert the team to mySQL
		$this->team->insert($this->mysqli);

		// fourth, update the team and post the changes to mySQL
		$newTeamCause = "feeding the coders";
		$this->team->setTeamCause($newTeamCause);
		$this->team->update($this->mysqli);


		// update the team and post the changes to mySQL
		$this->assertNotNull($this->team->getTeamId());
		$this->assertTrue($this->team->getTeamId() > 0);
		$this->assertIdentical($this->team->getTeamName(),	               			$this->TEAMNAME);
		$this->assertIdentical($this->team->getTeamCause(),			               $newTeamCause);




		//fifth delete the article
		$this->team->delete($this->mysqli);
		$this->team = null;

		//finally try to get the team and assert we didn't get a thing
		$hopefulTeam = Team::getTeamByTeamId($this->mysqli, $this->TEAMCAUSE);
		$this->assertNull($hopefulTeam);
	}

	// test deleting a team
	public function testDeleteTeam() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a team to post to mySQL
		$this->team = new team(null, $this->TEAMNAME, $this->TEAMCAUSE);

		// insert the team to mySQL
		$this->team->insert($this->mysqli);

		// verify the team was inserted
		$this->assertNotNull($this->team->getTeamId());
		$this->assertTrue($this->team->getTeamId() > 0);

		// delete the team
		$this->team->delete($this->mysqli);
		$this->team = null;

		// try to get the team and assert we didn't get a thing
		$hopefulTeam = team::getteamByTeamId($this->mysqli, $this->TEAMID);
		$this->assertNull($hopefulTeam);
	}

	// test grabbing a team from mySQL
	public function testGetTeamByTeamId () {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a team to post to mySQL
		$this->team = new Team(null, $this->TEAMNAME, $this->TEAMCAUSE);

		// third, insert the team to mySQL
		$this->team->insert($this->mysqli);

		// fourth, get the team using the static method
		$staticTeam = Team::getTeamByTeamId($this->mysqli, $this->team->getTeamId());

		// finally, compare the fields
		// finally, compare the fields
		$this->assertNotNull($staticTeam->getTeamId ());
		$this->assertTrue($staticTeam->getTeamId () > 0);
		$this->assertIdentical($staticTeam->getTeamId(),	 					$this->team->getTeamId());
		$this->assertIdentical($staticTeam->getTeamName(),						$this->TEAMNAME);
		$this->assertIdentical($staticTeam->getTeamCause(), 					$this->TEAMCAUSE);
	}

	// test grabbing a team from mySQL
	public function testGetTeamByTeamName () {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a team to post to mySQL
		$this->team = new Team(null, $this->TEAMNAME, $this->TEAMCAUSE);

		// third, insert the team to mySQL
		$this->team->insert($this->mysqli);

		// fourth, get the team using the static method
		$staticTeam = Team::getTeamByTeamName($this->mysqli, $this->TEAMNAME);

		// finally, compare the fields
		$this->assertNotNull($staticTeam->getTeamId ());
		$this->assertTrue($staticTeam->getTeamId () > 0);
		$this->assertIdentical($staticTeam->getTeamId(),	 					$this->team->getTeamId());
		$this->assertIdentical($staticTeam->getTeamName(),						$this->TEAMNAME);
		$this->assertIdentical($staticTeam->getTeamCause(), 					$this->TEAMCAUSE);
	}

	public function testTeamByTeamCause() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->team = new Team(null, $this->TEAMNAME, $this->TEAMCAUSE);
		$this->team1 = new Team(null, "WifeBeaters", $this->TEAMCAUSE);

		// third, insert the userTeam to mySQL
		$this->team->insert($this->mysqli);
		$this->team1->insert($this->mysqli);

		// fourth, get the userTeam using the static method
		$staticTeam = Team::getTeamByTeamCause($this->mysqli, $this->TEAMCAUSE);
		//finally, compare the fields
		for($i = 0; $i < count($staticTeam); $i++){
			$this->assertNotNull($staticTeam[$i]->getTeamId());
			$this->assertTrue($staticTeam[$i]->getTeamId() > 0);
			$this->assertIdentical($staticTeam[$i]->getTeamCause(), 		$this->TEAMCAUSE);
		}

		$this->team1->delete($this->mysqli);
	}
}
?>