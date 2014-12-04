<?php
/**
 * Unit test for User Id class
 * User: Martin
 * Date: 11/11/2014
 * Time: 1:24 PM
 */

// first require the SimpleTest framework
require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");

//then require class under scrutiny
require_once("../php/user.php");

//the UserIdTest for all our testsclass UserIdTest extends UnitTestCase {
class UserIdTest extends UnitTestCase{

	//variable to hold the test database row
	private $mysqli = null;
	//variable to hold the test database row
	private $user = null;
	private $user1 = null;

	//the rest of the "global" variables used to create test data

	private $USERNAME = "escissorhands";
	private $EMAIL = "getahaircut@yahoo.com";
	private $PASSWORD = "PASSWORD123";
	private $PASSWORDHASH = null;
	private $SALT = null;
	private $AUTHTOKEN = null;
	private $PERMISSIONS = 1;

	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp()
	{
		//connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

		// randomize the SALT, hash, and authentication token
		$i = rand(1, 1000);
		$this->SALT       = bin2hex(openssl_random_pseudo_bytes(32));
		$this->AUTHTOKEN = bin2hex(openssl_random_pseudo_bytes(16));
		$this->PASSWORDHASH       = hash_pbkdf2("sha512", $this->PASSWORD, $this->SALT, 2048, 128);

	}

	//Teardown (), a method to delete the test record and disconnect from mySQL
	public function tearDown(){
		// delete the user if we can
		if($this->user !== null) {
			$this->user->delete($this->mysqli);
			$this->user = null;
		}
	}

		// test creating a new user Id and inserting it to mySQL
	public function testInsertNewUserId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->USERNAME, $this->EMAIL, $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);
		$this->assertIdentical($this->user->getUserName(),	               	$this->USERNAME);
		$this->assertIdentical($this->user->getEmail(),            				$this->EMAIL);
		$this->assertIdentical($this->user->getPasswordHash(),					$this->PASSWORDHASH);
		$this->assertIdentical($this->user->getSalt(),								$this->SALT);
		$this->assertIdentical($this->user->getAuthToken(),						$this->AUTHTOKEN);
		$this->assertIdentical($this->user->getPermissions(),						$this->PERMISSIONS);
	}
// test updating a User in mySQL
	public function testUpdateUserId() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a user to post to mySQL
		$this->user = new User(null, $this->USERNAME, $this->EMAIL, $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, update the user and post the changes to mySQL
		$newEmail = "moluevan@yahoo.com";
		$this->user->setEmail($newEmail);
		$this->user->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);
		$this->assertIdentical($this->user->getUserName(),	               			$this->USERNAME);
		$this->assertIdentical($this->user->getEmail(),			               		$newEmail);
		$this->assertIdentical($this->user->getPasswordHash(),							$this->PASSWORDHASH);
		$this->assertIdentical($this->user->getSalt(),										$this->SALT);
		$this->assertIdentical($this->user->getAuthToken(),								$this->AUTHTOKEN);
		$this->assertIdentical($this->user->getPermissions() ,							$this->PERMISSIONS);

	}
	// test deleting a User Id
	public function testDeleteUserId() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a User to post to mySQL
		$this->user = new User(null, $this->USERNAME, $this->EMAIL, $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);

		// third, insert the User to mySQL
		$this->user->insert($this->mysqli);

		// fourth, verify the User was inserted
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);

		// fifth, delete the user
		$this->user->delete($this->mysqli);
		$this->user = null;

		// finally, try to get the user id and assert we didn't get a thing
		$hopefulUserId = User::getUserByUserId($this->mysqli, $this->EMAIL);
		$this->assertNull($hopefulUserId);
	}
	// test grabbing a user by userId from mySQL
	public function testGetUserByUserId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->USERNAME, $this->EMAIL, $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());

		// finally, compare the fields
		$this->assertNotNull($staticUser->getUserId ());
		$this->assertTrue($staticUser->getUserId () > 0);
		$this->assertIdentical($staticUser->getUserId(),	 				$this->user->getUserId());
		$this->assertIdentical($staticUser->getUserName(),					$this->USERNAME);
		$this->assertIdentical($staticUser->getEmail(), 					$this->EMAIL);
		$this->assertIdentical($staticUser->getPasswordHash(),			$this->PASSWORDHASH);
		$this->assertIdentical($staticUser->getSalt(),						$this->SALT);
		$this->assertIdentical($staticUser->getAuthToken(), 				$this->AUTHTOKEN);
		$this->assertIdentical($staticUser->getPermissions(), 			$this->PERMISSIONS);

	}

	// test grabbing a user by EMAIL from mySQL
	public function testGetUserByEmail() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->USERNAME, $this->EMAIL, $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticUser = User::getUserByEmail($this->mysqli, $this->EMAIL);

		// finally, compare the fields
		$this->assertNotNull($staticUser->getUserId ());
		$this->assertTrue($staticUser->getUserId () > 0);
		$this->assertIdentical($staticUser->getUserId(),	 				$this->user->getUserId());
		$this->assertIdentical($staticUser->getUserName(),					$this->USERNAME);
		$this->assertIdentical($staticUser->getEmail(), 					$this->EMAIL);
		$this->assertIdentical($staticUser->getPasswordHash(),			$this->PASSWORDHASH);
		$this->assertIdentical($staticUser->getSalt(),						$this->SALT);
		$this->assertIdentical($staticUser->getAuthToken(), 				$this->AUTHTOKEN);
		$this->assertIdentical($staticUser->getPermissions(), 			$this->PERMISSIONS);

	}

	// test grabbing a user by AuthToken from mySQL
	public function testGetUserByAuthToken() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->USERNAME, $this->EMAIL, $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticUser = User::getUserByAuthToken($this->mysqli, $this->AUTHTOKEN);

		// finally, compare the fields
		$this->assertNotNull($staticUser->getUserId ());
		$this->assertTrue($staticUser->getUserId () > 0);
		$this->assertIdentical($staticUser->getUserId(),	 				$this->user->getUserId());
		$this->assertIdentical($staticUser->getUserName(),					$this->USERNAME);
		$this->assertIdentical($staticUser->getEmail(), 					$this->EMAIL);
		$this->assertIdentical($staticUser->getPasswordHash(),			$this->PASSWORDHASH);
		$this->assertIdentical($staticUser->getSalt(),						$this->SALT);
		$this->assertIdentical($staticUser->getAuthToken(), 				$this->AUTHTOKEN);
		$this->assertIdentical($staticUser->getPermissions(), 			$this->PERMISSIONS);

	}


	// test grabbing a user by USERNAME from mySQL
	public function testGetUserByUserName() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->USERNAME, $this->EMAIL, $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticUser = User::getUserByUserName($this->mysqli, $this->USERNAME);

		// finally, compare the fields
		$this->assertNotNull($staticUser->getUserId ());
		$this->assertTrue($staticUser->getUserId () > 0);
		$this->assertIdentical($staticUser->getUserId(),	 				$this->user->getUserId());
		$this->assertIdentical($staticUser->getUserName(),					$this->USERNAME);
		$this->assertIdentical($staticUser->getEmail(), 					$this->EMAIL);
		$this->assertIdentical($staticUser->getPasswordHash(),			$this->PASSWORDHASH);
		$this->assertIdentical($staticUser->getSalt(),						$this->SALT);
		$this->assertIdentical($staticUser->getAuthToken(), 				$this->AUTHTOKEN);
		$this->assertIdentical($staticUser->getPermissions(), 			$this->PERMISSIONS);
	}

	public function testGetUserByPermissions() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->USERNAME, $this->EMAIL, $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);
		$this->user1 = new User(null, "jdoe", "tryingtopass@DDC.com", $this->PASSWORDHASH, $this->SALT, $this->AUTHTOKEN, $this->PERMISSIONS);

		// third, insert the userTeam to mySQL
		$this->user->insert($this->mysqli);
		$this->user1->insert($this->mysqli);

		// fourth, get the userTeam using the static method
		$staticUser = User::getUserByPermissions($this->mysqli, $this->PERMISSIONS);
		 //finally, compare the fields
		for($i = 0; $i < count($staticUser); $i++){
			$this->assertNotNull($staticUser[$i]->getUserId());
			$this->assertTrue($staticUser[$i]->getUserId() > 0);
			$this->assertIdentical($staticUser[$i]->getPermissions(), 		$this->PERMISSIONS);

		}

		$this->user1->delete($this->mysqli);
	}
}
