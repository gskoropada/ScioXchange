$(function() {
	$.ajax({
		type: "POST",
		url: "questions_backend.php",
		data: {
			slider: 1
		},
		success: function(data) {
			$("#question_slider").html(data);
			//slider();
		}
	});
	
});

function slider() {
	var questions = $("#question_slider").children("div").length;
	var i=1;
	while(i<questions) {
		setTimeout(function(i) {
			$("#question_slider:nth-child("+i+")").fadeOut();
			}
		, 1000);
	}
	i++;
	
}