/**
 * Created by Martin on 12/2/2014.
 */
//jQuery for signin form
// validate the form using jQuery
$(document).ready(function()
{
	$("#signIn").validate(
	{

			rules: {
				 userName: {
					required: true

				},
				passwordHash: {
					required: true

				}
			},

			messages: {
				userName : {
					required: "Please enter your Username or email"
				},
				passwordHash: {
					required: "Please enter your password"
				}


			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/form/signinprocessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
								setTimeout(function() {
									window.navigate("../index.php");
								}, 3000);
						}
					});
			}
		})
});