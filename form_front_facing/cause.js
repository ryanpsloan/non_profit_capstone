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
	$("#cause").validate(
		{

			rules: {
				causeName: {
					required: true

				},
				causeDescription: {
					required: true

				}

			},

			messages: {
				causeName : {
					required: "Please enter the cause you want to support."
				},
				causeDescription: {
					required: "Please enter a description of your cause."
				}


			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/form/causeprocessor.php",
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

