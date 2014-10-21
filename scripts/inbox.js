$(function(){
	$(".msg_short").click(function() {
		console.info($(this).attr('id'));
		$.ajax({
			type: 'POST',
			url: 'inbox_functions.php',
			data: {
				action: 'sp',
				id: $(this).attr('id')
			},
			success: function(data) {
				var msg = JSON.parse(data);
				console.log(msg);
				$("#inbox_preview").html("<div id='msg_header'></div>");
				$("#msg_header").append("<span class='msg_subject'>"+msg.subject+"</span>");
				$("#msg_header").append("<span class='msg_from'>From: "+msg.screenName+"</span><span class='msg_date'>Sent on: "+msg.timestamp+"</span>");
				$("#inbox_preview").append("<pre class='msg_content'>"+msg.content+"</pre>");
			}
		});
	});
	
});