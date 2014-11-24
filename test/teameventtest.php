<?php
//@author Dameon Smith

require_once("/usr/lib/php5/simpletest/autorun.php");
//then require the class under scrutiny
require_once("/etc/apache2/capstone-mysql/helpabq.php");
// ../ means go up one directory
require_once("../php/teamevent.php");

class TeamEventTest extends UnitTestCase {
	//variable to hold the mySQL connection
	private $mysqli = null;
	//variable to hold the test database row
	private $teamEvent  = null;
	private $event;
	private $team;
	//a few global variables for creating test data
	private $TEAMID = null;
	private $EVENTID = null;
	private $TEAMSTATUS = null;
	private $COMMENTPERMISSION = null;
	private $BANSTATUS	= null;

}