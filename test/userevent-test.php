<?php
/**
 * Will test the functionality of the UserEvent class
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */

//first require this SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
//then require the class under scrutiny
require_once("/etc/apache2/capstone-mysql/helpabq.php");
// ../ means go up one directory
require_once("../php/event.php");
require_once("../php/user.php");
require_once("../php/profile.php");
require_once("../php/userevent.php");
//the UserEventTest is a container for all our tests
class UserEventTest extends UnitTestCase
{
	//variable to hold the mySQL connection
	private $mysqli = null;
	//variable to hold the test database row
	private $userEvent = null;
	//a few global variables for creating test data

	private $user = null;
	private $profile = null;
	private $profile1 = null;
	private $event = null;
	private $event1 = null;

	private $EVENTID = null;
	private $PROFILEID = null;
	private $USEREVENTROLE = 1;
	private $COMMENTPERMISSION = 1;
	private $BANSTATUS = 1;
	//setUp() is a method that is run before each test
	//here, we use it to connect to mySQL and to calculate salt, authentication token and hash if we need it
	public function setUp()
	{
		// connect to mySQL
		// mysqli_report (MYSQLI_REPORT_STRICT);
		$this->mysqli = MysqliConfiguration::getMysqli();

		$salt = bin2hex(openssl_random_pseudo_bytes(32));
		$authToken = bin2hex(openssl_random_pseudo_bytes(16));
		$passwordHash = hash_pbkdf2("sha512", "password", $salt, 2048, 128);
		$i = rand(1, 1000);
		$this->user = new User(null, "igotthis". $i, "myhomie" . $i . "@yahoo.com", $passwordHash, $salt, $authToken, 2);
		$this->user->insert($this->mysqli);

		$this->profile = new Profile(null, $this->user->getUserId(), "Mr.", "John", "P", "Handcock", "I have the largest signature on the
				Declaration of Independence", "PM of Britain", "1600 Pennsylvania Ave", "SW", "Washington", "DC", "20500");
		$this->profile->insert($this->mysqli);

		$this->event = new Event(null, "BigEvent", new DateTime(), "The STEM center");
		$this->event->insert($this->mysqli);
	}

	//Teardown (), a method to delete the test record and disconnect from mySQL
	public function tearDown()
	{
		// delete the user if we can
		if($this->userEvent !== null) {
			$this->userEvent->delete($this->mysqli);
			$this->userEvent = null;
		}

		if($this->event !== null) {
			$this->event->delete($this->mysqli);
			$this->event = null;
		}

		if($this->profile !== null) {
			$this->profile->delete($this->mysqli);
			$this->profile = null;
		}

		if($this->user !== null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}
	}

	// test creating a new profile Id and comment Id and inserting it to mySQL
	public function testInsertNewUserEvent()
	{
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentUser to post to mySQL
		$this->userEvent = new UserEvent($this->profile->getProfileId(), $this->event->eventId,
													$this->USEREVENTROLE, $this->COMMENTPERMISSION,
													$this->BANSTATUS);

		// third, insert the commentUser, to mySQL
		$this->userEvent->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->userEvent->profileId);
		$this->assertTrue($this->userEvent->profileId > 0);
		$this->assertNotNull($this->userEvent->eventId);
		$this->assertTrue($this->userEvent->eventId > 0);
		$this->assertIdentical($this->userEvent->profileId, 				$this->profile->getProfileId());
		$this->assertIdentical($this->userEvent->eventId, 					$this->event->eventId);
		$this->assertIdentical($this->userEvent->userEventRole, 			$this->USEREVENTROLE);
		$this->assertIdentical($this->userEvent->commentPermission, 	$this->COMMENTPERMISSION);
		$this->assertIdentical($this->userEvent->banStatus, 				$this->BANSTATUS);
	}

	public function testUpdateUserEvent() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a user to post to mySQL
		$this->userEvent = new UserEvent($this->profile->getProfileId(), $this->event->eventId, $this->USEREVENTROLE,
			$this->COMMENTPERMISSION, $this->BANSTATUS);

		// insert the user to mySQL
		$this->userEvent->insert($this->mysqli);

		// fourth, update the user and post the changes to mySQL
		$newUserEventRole = 2;
		$this->userEvent->setUserEventRole($newUserEventRole);
		$this->userEvent->update($this->mysqli);


		// update the user and post the changes to mySQL
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue($this->event->eventId > 0);

		//fifth delete the article
		$this->userEvent->delete($this->mysqli);
		$this->userEvent = null;

		//finally try to get the user and assert we didn't get a thing
		$hopefulUserEvent = UserEvent::getUserEventByProfileId($this->mysqli, $this->profile->getProfileId());
		$this->assertNull($hopefulUserEvent);
	}

	//Tests the delete method
	public function testDeleteUserEvent()
	{
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a CommentUser to post to mySQL
		$this->userEvent = new UserEvent($this->profile->getProfileId(), $this->event->eventId, $this->USEREVENTROLE,
			$this->COMMENTPERMISSION, $this->BANSTATUS);

		// third, insert the CommentUser to mySQL
		$this->userEvent->insert($this->mysqli);

		// fourth, verify the CommentUser was inserted
		$this->assertNotNull($this->profile->getProfileId());
		$this->assertTrue($this->profile->getProfileId() > 0);
		$this->assertNotNull($this->event->eventId);
		$this->assertTrue($this->event->eventId > 0);

		// fifth, delete the commentUser
		$this->userEvent->delete($this->mysqli);
		$this->userEvent = null;

		// finally, try to get the userUser and assert we didn't get a thing
		$hopefulUserCommentId = UserEvent::getUserEventByEventId($this->mysqli, $this->profile->getProfileId(),
			$this->event->eventId);
		$this->assertNull($hopefulUserCommentId);
	}

	public function testGetUserEventByEventId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentUser to post to mySQL
		$this->userEvent = new UserEvent($this->profile->getProfileId(), $this->event->eventId, $this->USEREVENTROLE,
			$this->COMMENTPERMISSION, $this->BANSTATUS);

		// third, insert the commentUser to mySQL
		$this->userEvent->insert($this->mysqli);

		// fourth, get the commentUser using the static method
		$staticUserEvent = UserEvent::getUserEventByProfileId($this->mysqli, $this->event->eventId);

		// finally, compare the fields
		for($i = 0; $i < count($staticUserEvent); $i++){
			$this->assertNotNull($staticUserEvent[$i]->profileId);
			$this->assertTrue($staticUserEvent[$i]->profileId > 0);
			$this->assertNotNull($staticUserEvent[$i]->eventId);
			$this->assertTrue($staticUserEvent[$i]->eventId > 0);
			$this->assertIdentical($staticUserEvent[$i]->eventId,			 $this->event->eventId);
		}

	}

	public function testGetUserEventByProfileId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a commentUser to post to mySQL
		$this->userEvent = new UserEvent($this->profile->getProfileId(), $this->event->eventId, $this->USEREVENTROLE,
													$this->COMMENTPERMISSION, $this->BANSTATUS);

		// third, insert the commentUser to mySQL
		$this->userEvent->insert($this->mysqli);

		// fourth, get the commentUser using the static method
		$staticUserEvent = UserEvent::getUserEventByProfileId($this->mysqli, $this->profile->getProfileId());

		// finally, compare the fields
		for($i = 0; $i < count($staticUserEvent); $i++){
			$this->assertNotNull($staticUserEvent[$i]->profileId);
			$this->assertTrue($staticUserEvent[$i]->profileId > 0);
			$this->assertNotNull($staticUserEvent[$i]->eventId);
			$this->assertTrue($staticUserEvent[$i]->eventId > 0);
			$this->assertIdentical($staticUserEvent[$i]->profileId,			 $this->profile->getProfileId());
		}

	}

}