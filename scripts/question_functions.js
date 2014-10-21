//Displays a list of questions (encoded in JSON format) to the target object (String JQuery Selector)
function listQuestions(questions, target) {
	var i=0;
	for(i;i<questions.length;i++) {
		display(questions[i], target);
	}
}

//Appends one question (encoded in JSON format) to the target object (String JQuery Selector)
function display(question, target) {
	var d = new Date(question.timestamp);
	$(target).append("<div class='question click_option' onclick='redirect("+question.question_id+");'>" +
			"<p class='qtitle'>"+question.question_title+"</p><span class='qauthor'><a href='user_profile.php?id="+
			question.auth+"'>"+question.screenName+"</a></span><span class='qexcerpt'><pre>"+excerpt(question.q)+"</pre></span>"+
			"<span class='qstats'>"+d.toLocaleDateString()+" | "+replies(question.replies)+
			"<span class='tags'>"+tagLinks(question.tags)+"</span></span></div>");
}

//Shows one question (encoded in JSON format) inside the target object (String JQuery Selector)
function slide(question, target) {
	var d = new Date(question.timestamp);
	$(target).html("<div class='question click_option' onclick='redirect("+question.question_id+");'>" +
			"<p class='qtitle'>"+question.question_title+"</p><span class='qauthor'><a href='user_profile.php?id="+
			question.auth+"'>"+question.screenName+"</a></span><span class='qexcerpt'><pre>"+excerpt(question.q)+"</pre></span>"+
			"<span class='qstats'>"+d.toLocaleDateString()+" | "+replies(question.replies)+
			"<span class='tags'>"+tagLinks(question.tags)+"</span></span></div>");
}

//Returns a String value according to the number of replies.
function replies(n) {
	if(n == null || n == 0) {
		return "no replies";
	}else if(n == 1) {
		return "1 reply";
	} else {
		return n+" replies";
	}
}

//Returns a HTML formatted string with the links to the question tags. 
function tagLinks(tags) {
	var t = tags.split(",");
	var i;
	var links = "";
	for(i=0;i<t.length;i++){
		links = links +"<a href='tag.php?tag="+t[i].trim()+"'>"+t[i].trim()+"</a>";
	}
	return links;
}

//Returns an excerpt from the question body.
function excerpt(question) {
	if(question.length > 255) {
		question = question.substr(0,255) + "...";
	}
	return question;
}

//Redirects the browser to a detailed question view.
function redirect(id) {
	location.assign("question.php?id="+id);	
}