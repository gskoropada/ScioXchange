$(function() {
	$("#btnReply").click(function() {
		if($("#inAnswer").val().trim() != "") {
			$.ajax({
				type: "POST",
				url: "reply.php",
				data: {
					qid: $("#question_id").val(),
					answer: $("#inAnswer").val()
				},
				success: function(data) {
					console.info(data);
					location.reload();
				}
			});
		}
	});
});