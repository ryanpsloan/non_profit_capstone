/**
 *  Form test to test the Team form
 *  Created by Cass on 12/4/2014.
 */

// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../helpabq/form_front_facing/causeform.php");
	}
});

// global variables for form values
var VALID_CAUSENAME   = "Homeless Coders2";
var VALID_CAUSEDESCRIPTION  = "Overworked underpaid homeless coders";

var VALID_CAUSENAME1   = "Overworked Coders";
var VALID_CAUSEDESCRIPTIOPN1  = "Coders with no family life.";


// define a function to perform the actual unit tests
function testValidFields() {
	// fill in the form values
	F("#causeName").visible(function() {
		this.type(VALID_CAUSENAME);
	});
	F("#causeDescription").visible(function() {
		this.type(VALID_CAUSEDESCRIPTION);
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
		ok(F(this).html().indexOf("Thank you for supporting a cause to help ABQ.") >= 0, "successful message");
	});
}

function testInvalidFields() {
	// fill in the form values
	F("#causeName").visible(function() {
		this.type(VALID_CAUSENAME1);
	});
	F("#causeDescription").visible(function() {
		this.type(VALID_CAUSEDESCRIPTIOPN1);
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
// call for the tests to execute
test("test valid fields", testValidFields);
test("test invalid fields", testInvalidFields);