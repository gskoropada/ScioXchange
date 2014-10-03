$(function() {
	$.ajax({
		type: "POST",
		url: "tag_functions.php",
		data: {
			action: "cloud"
		},
		success: function(data) {
			words = JSON.parse(data);
			$("#wordcloud").jQCloud(words);
		}
	});
});