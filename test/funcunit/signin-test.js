// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../form_front_facing/signin.php");
	}
});

// global variables for form values
var VALID_userName  = "doughnuts@KK.com";
var VALID_passwordHash   = "lovemesomedoughnuts";



// define a function to perform the actual unit tests
function testValidFields() {
	// fill in the form values
	F("#userName").visible(function() {
		this.type(VALID_userName);
	});
	F("#passwordHash").visible(function() {
		this.type(VALID_passwordHash);
	});


// click the button once all the fields are filled in
	F("#profileSubmit").visible(function() {
		this.click();
	});

// in forms, we want to assert the form worked as expected
// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert-success"), "successful alert CSS");
		ok(F(this).html().indexOf("Welcome to HelpAbq.com") >= 0, "successful message");

	});
}
// the test function *MUST* be called in order for the test to execute
test("test valid fields", testValidFields);