<?php

// Get the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");

// then require the class under scrutiny
require_once("../php/cause.php");

// the causeTest is a container for all our tests
class CauseTest extends UnitTestCase {
	// variable to hold the mySQL connection
	private $mysqli = null;
	// variable to hold the test database row
	private $cause   = null;

	// a few "global" variables for creating test data
	private $CAUSEID		= null;
	private $CAUSENAME   	= "Run DMC";
	private $CAUSEDESCRIPTION   = "homeless coders";

	// setUp() is a method that is run before each test
	// here, we use it to connect to mySQL and to calculate the salt, hash, and authenticationToken
	public function setUp() {

		// connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();
	}

	// tearDown() is a method that is run after each test

	public function tearDown() {
		// delete the cause if we can
		if($this->cause !== null) {
			$this->cause->delete($this->mysqli);
			$this->cause = null;
		}
	}
	// test creating a new cause and inserting it to mySQL
	public function testInsertNewCause() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a cause to post to mySQL
		$this->cause = new cause(null, $this->CAUSENAME, $this->CAUSEDESCRIPTION);

		// third, insert the cause to mySQL
		$this->cause->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->cause->getCauseId());
		$this->assertTrue($this->cause->getCauseId() > 0);
		$this->assertIdentical($this->cause->getCauseName(),  			$this->CAUSENAME);
		$this->assertIdentical($this->cause->getCauseDescription(),    		$this->CAUSEDESCRIPTION);
	}

	// test updating a cause in mySQL
	public function testUpdateCause() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a cause to post to mySQL
		$this->cause = new Cause(null, $this->CAUSENAME, $this->CAUSEDESCRIPTION);

		// insert the cause to mySQL
		$this->cause->insert($this->mysqli);

		// fourth, update the cause and post the changes to mySQL
		$newCauseDescription = "feeding the coders";
		$this->cause->setCauseDescription($newCauseDescription);
		$this->cause->update($this->mysqli);


		// update the cause and post the changes to mySQL
		$this->assertNotNull($this->cause->getCauseId());
		$this->assertTrue($this->cause->getCauseId() > 0);
		$this->assertIdentical($this->cause->getCauseName(),	               			$this->CAUSENAME);
		$this->assertIdentical($this->cause->getCauseDescription(),			               $newCauseDescription);




		//fifth delete the article
		$this->cause->delete($this->mysqli);
		$this->cause = null;

		//finally try to get the cause and assert we didn't get a thing
		$hopefulCause = Cause::getCauseByCauseId($this->mysqli, $this->CAUSEDESCRIPTION);
		$this->assertNull($hopefulCause);
	}

	// test deleting a cause
	public function testDeleteCause() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a cause to post to mySQL
		$this->cause = new cause(null, $this->CAUSENAME, $this->CAUSEDESCRIPTION);

		// insert the cause to mySQL
		$this->cause->insert($this->mysqli);

		// verify the cause was inserted
		$this->assertNotNull($this->cause->getCauseId());
		$this->assertTrue($this->cause->getCauseId() > 0);

		// delete the cause
		$this->cause->delete($this->mysqli);
		$this->cause = null;

		// try to get the cause and assert we didn't get a thing
		$hopefulCause = cause::getcauseByCauseId($this->mysqli, $this->CAUSEID);
		$this->assertNull($hopefulCause);
	}

	// test grabbing a cause from mySQL
	public function testGetCauseByCauseId () {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a cause to post to mySQL
		$this->cause = new Cause(null, $this->CAUSENAME, $this->CAUSEDESCRIPTION);

		// third, insert the cause to mySQL
		$this->cause->insert($this->mysqli);

		// fourth, get the cause using the static method
		$staticCause = Cause::getCauseByCauseId($this->mysqli, $this->cause->getCauseId());

		// finally, compare the fields
		// finally, compare the fields
		$this->assertNotNull($staticCause->getCauseId ());
		$this->assertTrue($staticCause->getCauseId () > 0);
		$this->assertIdentical($staticCause->getCauseId(),	 					$this->cause->getCauseId());
		$this->assertIdentical($staticCause->getCauseName(),						$this->CAUSENAME);
		$this->assertIdentical($staticCause->getCauseDescription(), 					$this->CAUSEDESCRIPTION);
	}
}
?>