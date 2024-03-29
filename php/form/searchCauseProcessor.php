<?php
session_start();
require_once("/etc/apache2/capstone-mysql/helpabq.php");
require_once("csrf.php");
require_once("../cause.php");
require_once("../../form_front_facing/navbar.php");
require_once("../profile.php");
require_once("../user.php");
$mysqli    = MysqliConfiguration::getMysqli();

?>
<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.min.js"></script>
	<script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/additional-methods.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../../form_front_facing/searchCause.js"></script>
	<link type="text/css" rel="stylesheet" href="../../form_front_facing/sign.css"/>
	<link type="text/css" rel="stylesheet" href="../../index1.css"/>

	<title>Join Cause Form</title>
</head>
<body>
<?php navBarProcessor() ?>
<div class="container">

<?php
/**
 * Create cause search processor
 * User: Martin
 */

try {
	//verify the form was submitted properly

	if(@isset($_POST["causeName"]) === false) {
		throw(new RuntimeException("Please enter a cause to search by."));
	}

	// use filter_input to sanitize cause name
	$causeName = (filter_input(INPUT_POST, "causeName", FILTER_SANITIZE_STRING));

	// verify the CSRF tokens
	if(verifyCsrf($_POST["csrfName"], $_POST["csrfToken"]) === false) {
		throw(new RuntimeException("CSRF tokens incorrect or missing. Make sure cookies are enabled."));

	}
	echo "<div class=\"alert alert-info\" role=\"alert\"><strong><h1>Here are your results</strong></h1>  </div><br/> <br/>";
	// grab mysql data
	$causes = Cause::getCauseByCauseName($mysqli, $causeName);
	/**
	 * return the results to the user
	 */
	$resultCount = count($causes);
	for($i = 0; $i < $resultCount; $i++)	{
		$cause  = $causes[$i];

		echo "<div class='col-lg-12 miscItems' >";
		echo "<strong>" . $cause->getCauseName() . "</strong><br/>" .
			$cause->getCauseDescription()	.	"<br/>";


		echo "<form id=\"joinThings\" action=\"joinCauseProcessor.php\" method=\"POST\">";
		echo generateInputTags();
		echo "<input type = 'hidden' name = 'causeId' value = \"" . $cause->getCauseId() . "\">
		<button type ='submit' class='btn btn-primary'>Join Cause</button>
		</form>";
		echo "</div>";

	}


	if ($resultCount === 0 ) {
		echo "<strong><h2>no cause found</strong></h2>";
	}


} catch(Exception $exception) {
	echo "<div class=\"alert alert-danger\" role=\"alert\"><strong>Oh snap!</strong> Unable to search events" . $exception->getMessage() . "</div>";

}
?>

</div>
</body>
</html>