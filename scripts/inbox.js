/*
 * INBOX client side functions.
 */
$(function(){
	var c;
	if($(".sent_msgs").length > 0) { //Checks for the flag used by the server.
		c = "mcs";
		updateMsgList("fls"); //Get sent messages.
	} else {
		c = "mc";
		updateMsgList("fl"); //Get received messages.
	}
	
	$.ajax({ //Count unread messages and display
		type: 'POST',
		url: 'inbox_functions.php',
		data: {
			action: 'mcu'
		},
		success: function(data) {
			if(parseInt(data)!= 0){
				$("#unread_msg_count").text("("+data+")");
			}
		}
	});
	
	
	
	$.ajax({ //Count total messages and display
		type: 'POST',
		url: 'inbox_functions.php',
		data: {
			action: c
		},
		success: function(data) {
			if(parseInt(data)!= 0){
				$("#total_msg_count").text(data);
			}
		}
	});
	
});

function updateMsgList(action) { //Updates the message list displayed in the inbox.
	
	$.ajax({
		type: 'POST',
		url: 'inbox_functions.php',
		data: {
			action: action
		},
		success: function(data) {
			var list = JSON.parse(data);
			var i = 0;
			var id = 0;
			var d;
			$("#msg_list").html("");
			while(i<list.length){
				id = list[i].mid;
				d = new Date(list[i].timestamp);
				$("#msg_list").append("<div id='"+list[i].mid+"' class='msg_short click_option'></div>");
				$("#"+id).append("<span class='msg_subject'>"+list[i].subject+"</span>");
				$("#"+id).append("<span class='msg_from'>"+list[i].screenName+"</span>");
				$("#"+id).append("<span class='msg_date'>"+d.toLocaleDateString()+"</span>");
				
				if(list[i].reply_to != null) {
					d = new Date(list[i].rt_timestamp);
					$("#"+id).append("<div id='r_"+list[i].mid+"' class='msg_reply click_option'></div>");
					$("#r_"+id).append("<img src='images/reply_icon.png'>");
					$("#r_"+id).append("<span class='reply_from'>"+list[i].rt_screenName+"</span>");
					$("#r_"+id).append("<span class='reply_date'>"+d.toLocaleDateString()+"</span>");
				}
				
				if(i%2!=0) { 
					$("#"+id).addClass("alt_row");
				}
				
				if(list[i].r == 0) {
					$("#"+id).addClass("msg_unread");
				}
				
				$("#"+id).click(function() {
					showMessage($(this).attr('id'));
				});
				
				i++;
			}
		
			if($(".goto").length>0) {
				var goto_id = $(".goto").attr('name').trim();
				showMessage(goto_id);
			}
			
		}
	});
}

function showMessage(id) { //Displays a message on the preview pane and marks it as read
	
	$(".msg_highlight").removeClass("msg_highlight");
	$("#"+id).addClass("msg_highlight");

	$.ajax({
		type: 'POST',
		url: 'inbox_functions.php',
		data: {
			action: 'sp',
			id: id
		},
		success: function(data) {
			var msg = JSON.parse(data);
			if($(".sent_msgs").length>0){
				to_from = "To: "+msg.r_screenName;
			} else {
				to_from = "From: "+msg.screenName;
			}
			$("#inbox_preview").html("<div id='msg_header'></div>");
			$("#msg_header").append("<span class='msg_subject'>"+msg.subject+"</span>");
			$("#msg_header").append("<span class='msg_from'>"+to_from+"</span><span class='msg_date'>Sent on: "+msg.timestamp+"</span>");
			$("#inbox_preview").append("<pre class='msg_content'>"+msg.content+"</pre>");
		}
	});
	
	if($("#"+id).hasClass("msg_unread")) {		
		$.ajax({
			type: 'POST',
			url: 'inbox_functions.php',
			data: {
				action: 'mr',
				id: id
			},
			success: function(data) {
				if(parseInt(data)==1) {
					$("#"+id).removeClass("msg_unread");
				}
			}
		});
	}

}