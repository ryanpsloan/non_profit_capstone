/**
 * Created by dameo_000 on 17/12/2014.
 */


$(document).ready(function()
{
	$("#eventCreation").validate(
		{

			rules: {
				eventTitle: {
					required: true

				},
				eventDate: {
					required: true

				},
				eventLocation:{
					required: true
				}

			},

			messages: {
				eventTitle : {
					required: "Please enter the title for your event."
				},
				eventDate: {
					required: "Please enter the date the even will take place on."
				},
				eventLocation: {
					required: "Please enter the location that the event will take place."
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
