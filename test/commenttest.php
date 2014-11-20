<?php
/**
 * Unit test for comment
 *
 * @author Dameon Smith <dameonsmith76@gmail.com>
 */


require_once("/usr/lib/php5/simpletest/autorun.php");
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("../php/comment.php");

class CommentTest extends UnitTestCase {
	private $mysqli = null;

	private $comment = null;

	private $COMMENTID = null;

	private $COMMENTTEXT = "Hello this is a comment";

	private $COMMENTDATE = "2014-11-11 11:23:23";

	public function setUp() {
		// connect to mySQL
		mysqli_report (MYSQLI_REPORT_STRICT);
		$this->EVENTDATE = DateTime::createFromFormat("Y-m-d H:i:s", "1995-12-12 12:12:12");
	}
}
