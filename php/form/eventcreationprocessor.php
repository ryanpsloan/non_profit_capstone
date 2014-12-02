<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
include("../php/event.php");
$mysqli = MysqliConfiguration::getMysqli();


if(@isset($_POST['eventTitle'])=== false || @isset($_POST['eventDate']) || @isset($_POST['eventLocation'])){
	echo "<p>Form variables incomplete or missing. Please refill form</p>";
}

if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
	throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));
}

$newEvent = new Event(null, $_POST["eventTitle"],$_POST["eventDate"], $_POST["eventLocation"]);
$newEvent->insert($mysqli);

echo"<p>Event posted!</p>";
var_dump($newEvent);