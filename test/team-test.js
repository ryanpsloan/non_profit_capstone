/**
 * This is a test of the Team Form
 * Created by Cass on 12/4/2014.
 */

// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../helpabq/form_front_facing/teamform.php");
	}
});

// global variables for form values
var VALID_TEAMNAME   = "Homeless Coders2";
var VALID_TEAMCAUSE  = "Overworked underpaid homeless coders";

var VALID_TEAMNAME1   = "Homeless Coders";
var VALID_TEAMCAUSE1  = "Overworked underpaid homeless coders";


// define a function to perform the actual unit tests
function testValidFields() {
	// fill in the form values
	F("#teamName").visible(function() {
		this.type(VALID_TEAMNAME);
	});
	F("#teamCause").visible(function() {
		this.type(VALID_TEAMCAUSE);
	});

	// click the button once all the fields are filled in
	F("#Submit").visible(function() {
		this.click();
	});

	// assert the form worked as expected
	// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(F(this).html().indexOf("Thank you for joining a team") >= 0, "successful message");
	});
}

// define a function to perform the actual unit tests
function testInvalidFields() {
	// fill in the form values
	F("#teamName").visible(function() {
		this.type(VALID_TEAMNAME1);
	});
	F("#teamCause").visible(function() {
		this.type(VALID_TEAMCAUSE1);
	});

	// click the button once all the fields are filled in
	F("#Submit").visible(function() {
		this.click();
	});

	// assert the form worked as expected
	// here, we assert we got the success message from the AJAX call

	F(".alert").visible(function() {
		ok(F(this).hasClass("alert alert-danger"), "unsuccessful alert CSS");
		ok(F(this).html().indexOf("Oh snap") >= 0, "unsuccessful message");
		});
}
// call for the test to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);