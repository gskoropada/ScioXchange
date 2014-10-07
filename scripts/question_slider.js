$(function() {
	$.ajax({
		type: "POST",
		url: "questions_backend.php",
		data: {
			slider: 1
		},
		success: function(data) {		
			slider(JSON.parse(data));
		}
	});
	
});

function slider(questions) {
	var i=1;
	var delay = 5000;
	display(questions[0], "#question_slider");
	
	setInterval(function() {
		display(question[i],"#question_slider");

		i++;
		if(i==questions.length){
			i=0;
		}
	}, delay);
	
}
