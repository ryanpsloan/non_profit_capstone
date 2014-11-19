<?php
/**
 * Unit test for UserTeam class
 * User: Martin
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/user.php");
require_once("../php/profile.php");
require_once("../php/team.php");

//then require class under scrutiny
require_once("../php/userTeam.php");

//the UserTeamTest for all our testsclass UserTeamTest extends UnitTestCase {
class UserTeamTest extends UnitTestCase{

	//variable to hold the test database row
	private $mysqli = null;
	//variable to hold the test database row
	private $userTeam = null;

	//the rest of the "global" variables used to create test data
	private $ROLEINTEAM = 1;
	private $TEAMPERMISSION = 2;
	private $COMMMENTPERMISSION = 1;
	private $INVITEPERMISSION = 2;
	private $BANSTATUS = 1;

	private $user = null;
	private $profile = null;
	private $team = null;

	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp()
	{
		//connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

		$salt       = bin2hex(openssl_random_pseudo_bytes(32));
		$authToken = bin2hex(openssl_random_pseudo_bytes(16));
		$passwordHash       = hash_pbkdf2("sha512", "password", $salt, 2048, 128);
		$this->user = new User(null, "igotthis", "myhomie@yahoo.com", $passwordHash, $salt, $authToken, 2);
		$this->user->insert($this->mysqli);

		$this->profile = new Profile(null, $this->user->getUserId(),"Mr.","John", "P", "Handcock", "I have the largest signature on the
												Declaration of Independence", "PM of Britain", "1600 Pennsylvania Ave", "SW", "Washington",
												"DC", "20500");
		$this->profile->insert($this->mysqli);

		$this->team = new Team(null, "Luevano", "Domestic Violence");

		$this->team->insert($this->mysqli);

	}

	//Teardown (), a method to delete the test record and disconnect from mySQL
	public function tearDown(){
		// delete the user if we can
		if($this->userTeam !== null) {
			$this->userTeam->delete($this->mysqli);
			$this->userTeam = null;
		}

		if($this->team !== null) {
			$this->team->delete($this->mysqli);
			$this->team = null;
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

	// test creating a new profile Id and team Id and inserting it to mySQL
	public function testInsertNewUserTeam() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a userTeam to post to mySQL
		$this->userTeam = new UserTeam($this->profile->getProfileId(), $this->team->getTeamId(), $this->ROLEINTEAM, $this->TEAMPERMISSION, $this->COMMMENTPERMISSION,
												 $this->INVITEPERMISSION, $this->BANSTATUS);

		// third, insert the userTea, to mySQL
		$this->userTeam->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->userTeam->getProfileId());
		$this->assertTrue($this->userTeam->getProfileId() > 0);
		$this->assertNotNull($this->userTeam->getTeamId());
		$this->assertTrue($this->userTeam->getTeamId() > 0);
		$this->assertIdentical($this->userTeam->getProfileId(),							$this->profile->getProfileId());
		$this->assertIdentical($this->userTeam->getTeamId(),								$this->team->getTeamId());
		$this->assertIdentical($this->userTeam->getRoleInTeam(),	               	$this->ROLEINTEAM);
		$this->assertIdentical($this->userTeam->getTeamPermission(),            	$this->TEAMPERMISSION);
		$this->assertIdentical($this->userTeam->getCommentPermission(),				$this->COMMMENTPERMISSION);
		$this->assertIdentical($this->userTeam->getInvitePermission(),					$this->INVITEPERMISSION);
		$this->assertIdentical($this->userTeam->getBanStatus(),							$this->BANSTATUS);
	}
// test updating a profile and team in mySQL
	public function testUpdateUserTeam() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a user to post to mySQL
		$this->userTeam = new UserTeam($this->profile->getProfileId(), $this->team->getTeamId(), $this->ROLEINTEAM, $this->TEAMPERMISSION,
												 $this->COMMMENTPERMISSION, $this->INVITEPERMISSION, $this->BANSTATUS);

		// third, insert the userTeam to mySQL
		$this->userTeam->insert($this->mysqli);

		// fourth, update the userTeam and post the changes to mySQL
		$newBanStatus = 3;
		$this->userTeam->setBanStatus($newBanStatus);
		$this->userTeam->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->userTeam->getProfileId());
		$this->assertTrue($this->userTeam->getProfileId() > 0);
		$this->assertNotNull($this->userTeam->getTeamId());
		$this->assertTrue($this->userTeam->getTeamId() > 0);
		$this->assertIdentical($this->userTeam->getProfileId(),							$this->profile->getProfileId());
		$this->assertIdentical($this->userTeam->getTeamId(),								$this->team->getTeamId());
		$this->assertIdentical($this->userTeam->getRoleInTeam(),	               	$this->ROLEINTEAM);
		$this->assertIdentical($this->userTeam->getTeamPermission(),            	$this->TEAMPERMISSION);
		$this->assertIdentical($this->userTeam->getCommentPermission(),				$this->COMMMENTPERMISSION);
		$this->assertIdentical($this->userTeam->getInvitePermission(),					$this->INVITEPERMISSION);
		$this->assertIdentical($this->userTeam->getBanStatus(),							$newBanStatus);

	}
	// test deleting a UserTeam
	public function testDeleteUserTeam() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a UserTeam to post to mySQL
		$this->userTeam = new UserTeam($this->profile->getProfileId(), $this->team->getTeamId(), $this->ROLEINTEAM, $this->TEAMPERMISSION,
												 $this->COMMMENTPERMISSION, $this->INVITEPERMISSION, $this->BANSTATUS);

		// third, insert the UserTeam to mySQL
		$this->userTeam->insert($this->mysqli);

		// fourth, verify the UserTeam was inserted
		$this->assertNotNull($this->userTeam->getProfileId());
		$this->assertTrue($this->userTeam->getProfileId() > 0);
		$this->assertNotNull($this->userTeam->getTeamId());
		$this->assertTrue($this->userTeam->getTeamId() > 0);

		// fifth, delete the userteam
		$this->userTeam->delete($this->mysqli);
		$this->userTeam = null;

		// finally, try to get the userTeam and assert we didn't get a thing
		$hopefulUserTeamId = UserTeam::getUserTeamByBanStatus($this->mysqli, $this->BANSTATUS);
		$this->assertNull($hopefulUserTeamId);
	}
	// test grabbing a userTeam from mySQL
	public function testGetUserByBanStatus() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a userTeam to post to mySQL
		$this->userTeam = new UserTeam($this->profile->getProfileId(), $this->team->getTeamId(), $this->ROLEINTEAM, $this->TEAMPERMISSION,
												 $this->COMMMENTPERMISSION, $this->INVITEPERMISSION, $this->BANSTATUS);

		// third, insert the userTeam to mySQL
		$this->userTeam->insert($this->mysqli);

		// fourth, get the userTeam using the static method
		$staticUserTeam = User::getUserByProfileTeamId($this->mysqli, $this->userTeam->getProfileId(), $this->userTeam->getTeamId());

		// finally, compare the fields
		$this->assertNotNull($staticUserTeam->getProfileId());
		$this->assertTrue($staticUserTeam->getProfileId() > 0);
		$this->assertNotNull($staticUserTeam->getTeamId());
		$this->assertTrue($staticUserTeam->getTeamId() > 0);
		$this->assertIdentical($staticUserTeam->getProfileId(),							$this->profile->getProfileId());
		$this->assertIdentical($staticUserTeam->getTeamId(),								$this->team->getTeamId());
		$this->assertIdentical($staticUserTeam->getRoleInTeam(),	               	$this->ROLEINTEAM);
		$this->assertIdentical($staticUserTeam->getTeamPermission(),            	$this->TEAMPERMISSION);
		$this->assertIdentical($staticUserTeam->getCommentPermission(),				$this->COMMMENTPERMISSION);
		$this->assertIdentical($staticUserTeam->getInvitePermission(),					$this->INVITEPERMISSION);
		$this->assertIdentical($staticUserTeam->getBanStatus(),							$this->BANSTATUS);
	}


}