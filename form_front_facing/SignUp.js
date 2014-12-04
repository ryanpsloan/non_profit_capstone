/**
 * Created by Martin on 12/2/2014.
 */
//jQuery for signup form
// validate the form using jQuery
$(document).ready(function()
{
	$("#signUp").validate(
		{

			rules: {
				userName: {
					required: true

				},
				email: {
					required: true,
					email: true

				},
				password: {
					required: true

				},
				confPassword: {
					required: true,
					equalTo: "#password"

				},
				firstName: {
					required: true

				},
				lastName: {
					required: true

				},
				zipCode: {
					required: true

				}
			},

			messages: {
				userName : {
					required: "Please enter your Username"
				},
				email : {
					required: "Please enter your email"
				},
				password: {
					required: "Please enter your password"
				},
				confPassword: {
					// confirmPassword was empty
					required: "Please confirm the password",

					// passwords did not match
					equalTo: "Passwords do not match"
				},
				firstName : {
					required: "Please enter your First Name"
				},
				lastName : {
					required: "Please enter your Last Name"
				},
				zipCode : {
					required: "Please a valid Zip Code"
				}


			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/form/signupprocessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});