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
	
	$("#btnAsk").click(function() {
		$.ajax({
			type: "POST",
			url: "ask_ajax.php",
			data: {
				action: "sq",
				tags: $("#inTags").val(),
				question: $("#inQuestion").val(),
				title: $("#inTitle").val()
			},
			success: function(data) {
				if(data == 1) {
					alert("Question saved!");
					location.replace("questions.php");
				} else {
					$("#msgArea").text("There has been an error. Try again");
					console.info(data);
				}
			}
		});
	});
});