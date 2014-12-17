/**
 * Created by Cass on 12/9/2014.
 */
/**
 *
 */
//jQuery for team form
// validate the form using jQuery
$(document).ready(function()
{
	$("#team").validate(
		{

			rules: {
				teamName: {
					required: true

				},
				teamCause: {
					required: true

				}

			},

			messages: {
				teamName : {
					required: "Please enter your Team name"
				},
				cause : {
					required: "Please enter your cause"
				}


			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/form/teamprocessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
							setTimeout(function() {
								window.location = "../index.php";
							}, 3000);
						}
					});
			}
		})
});
