$(function() {
	$("#inTags").keyup(function() {
		$.ajax({
			type: "POST",
			url: "tag_functions.php",
			data: {
				action: "st",
				tags: $("#inTags").val()
			},
			success: function(data) {
				$("#tagSuggest").html(data);
			}
		});
	});
});