/**
 * Created by Martin on 12/2/2014.
 */
//jQuery for searchCause form
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
					required: "Please enter a search parameter"
				}

			}

			//submitHandler: function(form) {
			//	$(form).ajaxSubmit(
			//		{
			//			type   : "POST",
			//			url    : "../php/form/searchTeamProcessor.php",
			//			success: function(ajaxOutput) {
			//				$("#outputArea").html(ajaxOutput);
			//			}
			//		});
			//}
	})
});