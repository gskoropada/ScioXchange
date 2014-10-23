$(function() {
	$("#not_counter").click(function(){
		$.ajax({
			type: "POST",
			url: "notify.php",
			data: {
				get_notifications: 1
			},
			success: function(notifications) {
				showNotifications(notifications);
				setTimeout(function() {ackNotifications();}, 500);
			}
		});
	});
	
	setInterval(function() {updateCounter();}, 1000*60*5);
});

function showNotifications(notifications) {
	var i;
	nots_JSON = JSON.parse(notifications);
	$("#notifications").html("<div onclick=\"$('#notifications').addClass('hidden');\" class='close_not click_option'>x</div>");
	for(i=0; i<nots_JSON.length;i++) {
		origin = nots_JSON[i].origin;
		target = nots_JSON[i].parent;
		id = nots_JSON[i].not_id;
		status = parseInt(nots_JSON[i].status);
		switch(nots_JSON[i].origin_type) {
		case "0":
			link = "question.php?id="+origin;
			break;
		case "1":
			link = "question.php?id="+target+"#ans_"+origin;
			break;
		case "2":
			link = "inbox.php?goto="+target;
			break;
		}
		switch(nots_JSON[i].not_type){
		case "0":
			msg = "Someone answered your question!";
			break;
		case "1":
			msg = "You have received a comment on your ";
			if(nots_JSON[i].origin_type==0) {
				msg = msg + "question!";
			} else if(nots_JSON[i].origin_type==1) {
				msg = msg + "answer!";
			}
			break;
		case "2":
			msg = "Your answer has received a vote!";
			break;
		case "3":
			msg = "Someone sent you a message!";
			break;
		}
		if(status == 1) {
			read = "not_read";
		} else {
			read = "";
		}
		
		$("#notifications").append("<div id='"+origin+"' class='not_msg "+read+"'><a href='"+link+"' >"+msg+"</a></div>");
		$("#notifications").removeClass("hidden");
	}
}

function ackNotifications() {
	$.ajax({
		type: "POST",
		url: "notify.php",
		data: {
			ack: 1
		},
		success: function(data) {
			if(data=="ACK") {
				showNotifications(updateNotifications());
				updateCounter();
			}
		}
	});
}

function updateNotifications() {
	var notifications = {"response":""};
	$.ajax({
		type: "POST",
		url: "notify.php",
		data: {
			get_notifications: 1
		},
		success: function(data) {
			notifications.response = data;
		},
		async: false
	});

	return notifications.response;
}

function updateCounter() {
	$.ajax({
		type: "POST",
		url: "notify.php",
		data: {
			count: 1
		},
		success: function(data) {
			$("#not_counter").text(data);
		}
	});
}