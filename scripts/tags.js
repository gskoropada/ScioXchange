$(function() {
	$.ajax({
		type: "POST",
		url: "questions_backend.php",
		data: {
			tag: $("#id_tag").val(),
			limit: 10,
			offset: 0
		},
		success: function(data) {
			console.info(data);
			var target = $("#tag_questions"); 
			listQuestions(JSON.parse(data), "#tag_questions");
		}
	});
	

});
