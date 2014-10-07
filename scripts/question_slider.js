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
	var delay = 10000;
	display(questions,0);
	
	setInterval(function() {
		display(questions, i);

		i++;
		if(i==questions.length){
			i=0;
		}
	}, delay);
	
}

function display(questions, i) {
	d = new Date(questions[i].timestamp);
	$("#question_slider").html("<div id='"+questions[i].question_id+"' class='question click_option'>" +
			"<p class='qtitle'>"+questions[i].question_title+"</p><span class='qauthor'><a href='user_profile.php?id="+
			questions[i].auth+"'>"+questions[i].screenName+"</a></span><span class='qexcerpt'>"+questions[i].q+"</span>"+
			"<span class='qstats'>"+d.toLocaleDateString()+" | "+replies(questions[i].replies)+
			"<span class='tags'>"+tagLinks(questions[i].tags)+"</span></span></div>");
}

function replies(n) {
	if(n == null || n == 0) {
		return "no replies";
	}else if(n == 1) {
		return "1 reply";
	} else {
		return n+" replies";
	}
}

function tagLinks(tags) {
	var t = tags.split(",");
	var i;
	var links = "";
	for(i=0;i<t.length;i++){
		links = links +"<a href='#'>"+t[i]+"</a>";
	}
	return links;
}