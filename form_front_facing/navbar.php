<?php
/**
 * Function to make the navBar reusable and dynamic.
 *
 * @author Casimiro Vigil
 * @author Dameon Smith
 */
require_once("/etc/apache2/capstone-mysql/helpabq.php");


function navBar()
{
	$mysqli    = MysqliConfiguration::getMysqli();
	//NOTICE NEED TO INCLUDE USER AND PROFILE ON ALL PAGES USING THE NAV BAR
	//FIXME Hard code links to my upload
	if(@isset($_SESSION['userId']) === false){
		$signInStatus = "<li><a href='https://bootcamp-coders.cnm.edu/~dsmith/helpabq/form_front_facing/signin.php'>
							  Sign In</a></li>
							 <li><a href='https://bootcamp-coders.cnm.edu/~dsmith/helpabq/form_front_facing/SignUp.php'>
							 Sign Up</a></li>";
	} elseif(@isset($_SESSION['userId']) === true){
		$userId = $_SESSION['userId'];
		$profile = Profile::getProfileByUserId($mysqli, $userId);
		$signInStatus = "<li><a>Welcome " . $profile->getFirstName() . ' ' . $profile->getLastName() . "</a></li>
							  <li><a href='https://bootcamp-coders.cnm.edu/~dsmith/helpabq/php/form/signOut.php'>
							  Sign Out</a></li>";
	} else {
		$signInStatus = "<li><a href='https://bootcamp-coders.cnm.edu/~dsmith/helpabq/form_front_facing/signin.php'>
								Sign In</a></li>
								<li><a href='https://bootcamp-coders.cnm.edu/~dsmith/helpabq/form_front_facing/SignUp.php'>
								Sign Up</a></li>";
	}

	echo <<< EOF
<header>
		<div class="container">
			<aside class="logo col-lg-2">
			<a href="#">
				<img src="images/HelpAcrossABQ.png" style="height: 5em; width: 5em;">
			</a>
			</aside>
			<div class="col-lg-10">
				<nav class="navbar navbar-default" role="navigation">
				<ul class="nav navbar-nav navbar-right">
					<!--Three Drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cause <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="form_front_facing/searchCause.php">Find a Cause to Support</a></li>
							<li><a href="form_front_facing/causeform.php">Enter a Cause Description</a></li>
						</ul>
					</li>
					<!-- create the Event Button with drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Event<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="form_front_facing/searchEvent.php">Find an Event</a></li>
							<li><a href="form_front_facing/eventform.php">Create an Event</a></li>
						</ul>
					</li>
					<!-- create the Team Button with drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Team<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="form_front_facing/teamform.php">Create a Team</a></li>
							<li><a href="form_front_facing/searchTeam.php">Join a Team</a></li>
							<!--<li><a href="#">Delete a team</a></li>-->
						</ul>
					</li>
					$signInStatus
				</ul>
			</nav>
			</div>
		</div>
	</header>"
EOF;
}

function navBarForm(){
	$mysqli    = MysqliConfiguration::getMysqli();
	//NOTICE NEED TO INCLUDE USER AND PROFILE ON ALL PAGES USING THE NAV BAR
	//FIXME Hard code links to my upload
	if(@isset($_SESSION['userId']) === false){
		$signInStatus = "<li><a href='signin.php'>
							  Sign In</a></li>
							 <li><a href='SignUp.php'>
							 Sign Up</a></li>";
	} elseif(@isset($_SESSION['userId']) === true){
		$userId = $_SESSION['userId'];
		$profile = Profile::getProfileByUserId($mysqli, $userId);
		$signInStatus = "<li><a>Welcome " . $profile->getFirstName() . ' ' . $profile->getLastName() . "</a></li>
							  <li><a href='../php/form/signOut.php'>
							  Sign Out</a></li>";
	} else {
		$signInStatus = "<li><a href='signin.php'>
								Sign In</a></li>
								<li><a href='SignUp.php'>Sign Up</a></li>";
	}

	echo <<< EOF
<header>
		<div class="container">
			<aside class="logo col-lg-2">
			<a href="../index.php">
				<img src="../images/HelpAcrossABQ.png" style="height: 5em; width: 5em;">
			</a></aside>
			<div class="col-lg-10">
				<nav class="navbar navbar-default" role="navigation">
				<ul class="nav navbar-nav navbar-right">
					<!--Three Drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cause <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="searchCause.php">Find a Cause to Support</a></li>
							<li><a href="causeform.php">Enter a Cause Description</a></li>
						</ul>
					</li>
					<!-- create the Event Button with drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Event<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="searchEvent.php">Find an Event</a></li>
							<li><a href="eventform.php">Create an Event</a></li>
						</ul>
					</li>
					<!-- create the Team Button with drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Team<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="teamform.php">Create a Team</a></li>
							<li><a href="searchTeam.php">Join a Team</a></li>
							<!--<li><a href="#">Delete a team</a></li>-->
						</ul>
					</li>
					$signInStatus
				</ul>
			</nav>
			</div>
		</div>
	</header>"
EOF;
}

function navBarProcessor(){
	$mysqli    = MysqliConfiguration::getMysqli();
	//NOTICE NEED TO INCLUDE USER AND PROFILE ON ALL PAGES USING THE NAV BAR
	//FIXME Hard code links to my upload
	if(@isset($_SESSION['userId']) === false){
		$signInStatus = "<li><a href='../../form_front_facing/signin.php'>
							  Sign In</a></li>
							 <li><a href='../../form_front_facing/SignUp.php'>
							 Sign Up</a></li>";
	} elseif(@isset($_SESSION['userId']) === true){
		$userId = $_SESSION['userId'];
		$profile = Profile::getProfileByUserId($mysqli, $userId);
		$signInStatus = "<li><a>Welcome " . $profile->getFirstName() . ' ' . $profile->getLastName() . "</a></li>
							  <li><a href='signOut.php'>
							  Sign Out</a></li>";
	} else {
		$signInStatus = "<li><a href='../../form_front_facing/signin.php'>
								Sign In</a></li>
								<li><a href='../../form_front_facing/SignUp.php'>
								Sign Up</a></li>";
	}

	echo <<< EOF
<header>
		<div class="container">
			<aside class="logo col-lg-2">
			<a href="../../index.php">
				<img src="../../images/HelpAcrossABQ.png" style="height: 5em; width: 5em;">
			</a>
			</aside>
			<div class="col-lg-10">
				<nav class="navbar navbar-default" role="navigation">
				<ul class="nav navbar-nav navbar-right">
					<!--Three Drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cause <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="../../form_front_facing/searchCause.php">Find a Cause to Support</a></li>
							<li><a href="../../form_front_facing/causeform.php">Enter a Cause Description</a></li>
						</ul>
					</li>
					<!-- create the Event Button with drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Event<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="../../form_front_facing/searchEvent.php">Find an Event</a></li>
							<li><a href="../../form_front_facing/eventform.php">Create an Event</a></li>
						</ul>
					</li>
					<!-- create the Team Button with drop downs -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Team<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="../../form_front_facing/teamform.php">Create a Team</a></li>
							<li><a href="../../form_front_facing/searchTeam.php">Join a Team</a></li>
							<!--<li><a href="#">Delete a team</a></li>-->
						</ul>
					</li>
					$signInStatus
				</ul>
			</nav>
			</div>
		</div>
	</header>"
EOF;
}