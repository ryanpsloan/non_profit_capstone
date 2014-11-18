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

	//the rest of the "global" variables used to create test data
	private $userName = "escissorhands";
	private $email = "getahaircut@yahoo.com";
	private $password = "password123";
	private $passwordHash = null;
	private $salt = null;
	private $authToken = null;
	private $permissions = 1;

	//setUp () is the first step in unit testing and is a method to run before each test
	//here it connects to mySQL
	public function setUp()
	{
		//connect to mySQL
		$this->mysqli = MysqliConfiguration::getMysqli();

		// randomize the salt, hash, and authentication token
		$this->salt       = bin2hex(openssl_random_pseudo_bytes(32));
		$this->authToken = bin2hex(openssl_random_pseudo_bytes(16));
		$this->passwordHash       = hash_pbkdf2("sha512", $this->password, $this->salt, 2048, 128);

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
		$this->user = new User(null, $this->userName, $this->email, $this->passwordHash, $this->salt, $this->authToken, $this->permissions);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);
		$this->assertIdentical($this->user->getUserName(),	               	$this->userName);
		$this->assertIdentical($this->user->getEmail(),            				$this->email);
		$this->assertIdentical($this->user->getPasswordHash(),					$this->passwordHash);
		$this->assertIdentical($this->user->getSalt(),								$this->salt);
		$this->assertIdentical($this->user->getAuthToken(),						$this->authToken);
		$this->assertIdentical($this->user->getPermissions(),						$this->permissions);
	}
// test updating a User in mySQL
	public function testUpdateUserId() {
		// verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// create a user to post to mySQL
		$this->user = new User(null, $this->userName, $this->email, $this->passwordHash, $this->salt, $this->authToken, $this->permissions);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, update the user and post the changes to mySQL
		$newEmail = "moluevan@yahoo.com";
		$this->user->setEmail($newEmail);
		$this->user->update($this->mysqli);

		// finally, compare the fields
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);
		$this->assertIdentical($this->user->getUserName(),	               			$this->userName);
		$this->assertIdentical($this->user->getEmail(),			               		$newEmail);
		$this->assertIdentical($this->user->getPasswordHash(),							$this->passwordHash);
		$this->assertIdentical($this->user->getSalt(),										$this->salt);
		$this->assertIdentical($this->user->getAuthToken(),								$this->authToken);
		$this->assertIdentical($this->user->getPermissions() ,							$this->permissions);

	}
	// test deleting a User Id
	public function testDeleteUserId() {
		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a User to post to mySQL
		$this->user = new User(null, $this->userName, $this->email, $this->passwordHash, $this->salt, $this->authToken, $this->permissions);

		// third, insert the User to mySQL
		$this->user->insert($this->mysqli);

		// fourth, verify the User was inserted
		$this->assertNotNull($this->user->getUserId());
		$this->assertTrue($this->user->getUserId() > 0);

		// fifth, delete the user
		$this->user->delete($this->mysqli);
		$this->user = null;

		// finally, try to get the user id and assert we didn't get a thing
		$hopefulUserId = User::getUserByUserId($this->mysqli, $this->email);
		$this->assertNull($hopefulUserId);
	}
	// test grabbing a user id from mySQL
	public function testGetUserByUserId() {

		// first, verify mySQL connected OK
		$this->assertNotNull($this->mysqli);

		// second, create a user to post to mySQL
		$this->user = new User(null, $this->userName, $this->email, $this->passwordHash, $this->salt, $this->authToken, $this->permissions);

		// third, insert the user to mySQL
		$this->user->insert($this->mysqli);

		// fourth, get the user using the static method
		$staticUser = User::getUserByUserId($this->mysqli, $this->user->getUserId());

		// finally, compare the fields
		$this->assertNotNull($staticUser->getUserId ());
		$this->assertTrue($staticUser->getUserId () > 0);
		$this->assertIdentical($staticUser->getUserId(),	 				$this->user->getUserId());
		$this->assertIdentical($staticUser->getUserName(),					$this->userName);
		$this->assertIdentical($staticUser->getEmail(), 					$this->email);
		$this->assertIdentical($staticUser->getPasswordHash(),			$this->passwordHash);
		$this->assertIdentical($staticUser->getSalt(),						$this->salt);
		$this->assertIdentical($staticUser->getAuthToken(), 				$this->authToken);
		$this->assertIdentical($staticUser->getPermissions(), 			$this->permissions);

	}


}
