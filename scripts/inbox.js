$(function(){
	
	updateMsgList();
	
});

function updateMsgList() {
	
	$.ajax({
		type: 'POST',
		url: 'inbox_functions.php',
		data: {
			action: 'fl'
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
		}
	});
}

function showMessage(id) {
	
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
			$("#inbox_preview").html("<div id='msg_header'></div>");
			$("#msg_header").append("<span class='msg_subject'>"+msg.subject+"</span>");
			$("#msg_header").append("<span class='msg_from'>From: "+msg.screenName+"</span><span class='msg_date'>Sent on: "+msg.timestamp+"</span>");
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