/**
 * Created by Martin on 12/2/2014.
 */
//jQuery for signin form
// validate the form using jQuery
$(document).ready(function()
{
	$("#searchEvent").validate(
		{

			rules: {
				eventTitle: {
					required: true

				}
			},

			messages: {
				eventTitle : {
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