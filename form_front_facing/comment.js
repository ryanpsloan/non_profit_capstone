$(document).ready(function ()
{
	$("#commentForm").validate(
		{

		rules: {
			comment: {
				required: true
			}
		},

		messages: {
			comment: {
				required: "Please type a comment."
			}
		},

		submitHandler: function(form) {
			$(form).ajaxSubmit(
				{
					type   : "POST",
					url    : "../php/form/commentprocessor.php",
					success: function(ajaxOutput) {
						$("#outputArea").html(ajaxOutput);
					}
				});
			}
		})

});