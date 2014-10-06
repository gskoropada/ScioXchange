var socket;
var chatID;
$(function() {
	socket = new WebSocket("ws://127.0.0.1:2500");
	
	socket.onopen = function(ev) {
		register();
	};
	
	socket.onmessage = function(ev) {
		response = JSON.parse(ev.data);
		switch(response.opt){
		case "^NC":
			chatID = response.chid;
			$("body").append("<pre><textarea id='msg_area'></textarea></pre>");
			$("body").append("<input type='text' id='cht_msg'>");
			$("body").append("<button id='snd_msg'>Send</button>");
			$("#snd_msg").click(function() {
				sendMsg(chatID, $("#cht_msg").val());
				$("#cht_msg").val("");
			});
			break;
		case "^MSG":
			$("#msg_area").append(response.msg);
		}
	};
	
	$("body").append("<div id='chat_window'></div>");
	$("body").append("<button id='start_chat'>Start chatting</button>");
	$("#start_chat").click(function() {
		startChat($("#to").text());
	});
	
});

function register() {
	var regMsg = {
		opt: "register",
		usr: $("#usr").text()
	};
	
	socket.send(JSON.stringify(regMsg));
}

function startChat(user) {
	var chat_start = {
			opt: "start",
			usr: $("#usr").text(),
			to: user
	};
	
	socket.send(JSON.stringify(chat_start));
}

function sendMsg(chid, msg) {
	var obj_msg = {
			opt: "^MSG",
			chid: chid,
			msg: msg,
			usr: $("#usr").text()
	};
	
	socket.send(JSON.stringify(obj_msg));
}