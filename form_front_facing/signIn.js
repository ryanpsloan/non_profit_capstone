/**
 * Created by Martin on 12/2/2014.
 */
//jQuery for signin form
// validate the form using jQuery
$(document).ready(function()
{
	$("#searchTeam").validate(
	{

			rules: {
				 teamName: {
					required: true

				}
			},

			messages: {
				teamName : {
					required: "Please enter your teamName to search"
				}


			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/form/joinTeamProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});