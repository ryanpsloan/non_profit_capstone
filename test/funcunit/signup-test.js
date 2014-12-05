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
	ok(F(this).hasClass("alert-success"), "successful alert CSS");
	ok(F(this).html().indexOf("Sign up successful") >= 0, "successful message");

});
