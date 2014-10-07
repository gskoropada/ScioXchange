$(function() {
	
	var id;
	
	$.ajax({
		url: "dashboard_functions.php",
		type: "POST",
		data: {
			action: "id"
		},
		success: function(data) {
			id = parseInt(data);
			$.ajax({
				type: "POST",
				url: "dashboard_functions.php",
				data: {
					action: "qc",
					id: id
						},
				success: function(data) {
					$("#numOfQuest").text(data);
				}
			});
			
			$.ajax({
				type: "POST",
				url: "dashboard_functions.php",
				data: {
					action: "ac",
					id: id
						},
				success: function(data) {
					$("#numOfAns").text(data);
				}
			});
			
			$.ajax({
				type: "POST",
				url: "questions_backend.php",
				data: {
					fetch: 10,
					id: id,
					offset: 0
				},
				success: function(data) {
					console.info(JSON.parse(data));
					listQuestions(JSON.parse(data));
				}
			});
			
		}	
	});
	
});

function listQuestions(questions) {
	var i=0;
	for(i;i<questions.length;i++) {
		display(questions[i]);
	}
}

function display(question) {
	var d = new Date(question.timestamp);
	$("#usrQuestions").append("<div id='"+question.question_id+"' class='question click_option'>" +
			"<p class='qtitle'>"+question.question_title+"</p><span class='qauthor'><a href='user_profile.php?id="+
			question.auth+"'>"+question.screenName+"</a></span><span class='qexcerpt'><pre>"+excerpt(question.q)+"</pre></span>"+
			"<span class='qstats'>"+d.toLocaleDateString()+" | "+replies(question.replies)+
			"<span class='tags'>"+tagLinks(question.tags)+"</span></span></div>");
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

function excerpt(question) {
	if(question.length > 255) {
		question = question.substr(0,255) + "...";
	}
	return question;
}