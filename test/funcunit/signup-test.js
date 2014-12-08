
// open a new window with the form under scrutiny
module("tabs", {
	setup: function() {
		F.open("../../form_front_facing/signUp.php");
	}
});

var VALID_userName  = "msimpson2";
var VALID_email  = "nodoughnuts2@KK.com";
var VALID_password   = "dontlovemesomedoughnuts";
var VALID_confPassword    = "dontlovemesomedoughnuts";
var VALID_firstName = "Marge";
var VALID_lastName = "Simpson";
var VALID_zipCode = "55555";




// global variables for form values
var VALID_userName1  = "hsimpson";
var VALID_email1  = "doughnuts@KK.com";
var VALID_password1  = "lovemesomedoughnuts";
var VALID_confPassword1   = "lovemesomedoughnuts";
var VALID_firstName1= "Homer";
var VALID_lastName1 = "Simpson";
var VALID_zipCode1 = "55555";


// define a function to perform the actual unit tests
function testValidFields() {
	// fill in the form values
	F("#userName").visible(function() {
		this.type(VALID_userName);
	});
	F("#email").visible(function() {
		this.type(VALID_email);
	});
	F("#password").visible(function() {
		this.type(VALID_password);
	});
	F("#confPassword").visible(function() {
		this.type(VALID_confPassword);
	});
	F("#firstName").visible(function() {
		this.type(VALID_firstName);
	});
	F("#lastName").visible(function() {
		this.type(VALID_lastName);
	});
	F("#zipCode").visible(function() {
		this.type(VALID_zipCode);
	});

// click the button once all the fields are filled in
	F("#profileSubmit").visible(function() {
		this.click();
	});

// in forms, we want to assert the form worked as expected
// here, we assert we got the success message from the AJAX call
	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert alert-success"), "successful alert CSS");
		ok(F(this).html().indexOf("Sign up successful") >= 0, "successful message");
	});

}
// define a function to perform the actual unit tests
function testInvalidFields() {
	//for(var i = 0; i < 2; i++) {
		// fill in the form values
		F("#userName").visible(function() {
			this.type(VALID_userName1);
		});
		F("#email").visible(function() {
			this.type(VALID_email1);
		});
		F("#password").visible(function() {
			this.type(VALID_password1);
		});
		F("#confPassword").visible(function() {
			this.type(VALID_confPassword1);
		});
		F("#firstName").visible(function() {
			this.type(VALID_firstName1);
		});
		F("#lastName").visible(function() {
			this.type(VALID_lastName1);
		});
		F("#zipCode").visible(function() {
			this.type(VALID_zipCode1);
		});

		// click the button once all the fields are filled in
		F("#profileSubmit").visible(function() {
			this.click();
		});

		// reset the form so we can repeat the sign up

	//}

	F(".alert").visible(function() {
		// the ok() function from qunit is equivalent to SimpleTest's assertTrue()
		ok(F(this).hasClass("alert alert-danger"), "unsuccessful alert CSS");
		ok(F(this).html().indexOf("Oh snap") >= 0, "unsuccessful message");
	});

}
// the test function *MUST* be called in order for the test to execute
	test("test valid fields", testValidFields);
			test("test invalid fields", testInvalidFields);


