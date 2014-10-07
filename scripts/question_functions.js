function listQuestions(questions, target) {
	var i=0;
	for(i;i<questions.length;i++) {
		display(questions[i], target);
	}
}

function display(question, target) {
	var d = new Date(question.timestamp);
	$(target).append("<div class='question click_option' onclick='redirect("+question.question_id+");'>" +
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
		links = links +"<a href='tag.php?tag="+t[i].trim()+"'>"+t[i].trim()+"</a>";
	}
	return links;
}

function excerpt(question) {
	if(question.length > 255) {
		question = question.substr(0,255) + "...";
	}
	return question;
}

function redirect(id) {
	location.assign("question.php?id="+id);	
}