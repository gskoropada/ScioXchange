$(function() {
	$(".qredirect").click(function() {
		location.assign("question.php?id="+$(this).attr('id'));
	});
	
	$("#btnAsk").click(function() {
		location.assign("ask.php");
	});
	
	$(".btnAComment").click(function() {
		id = $(this).attr('id');
		$("#ans_"+id).append("<div id='acomm_"+id+"' class='ans_comment'></div>");
		$("#acomm_"+id).append("<textarea id='comm_"+id+"' placeholder='Type your comment here'></textarea>");
		$("#acomm_"+id).append("<span class='click_option' id='aCommSubmit_"+id+"'>Submit</span>");
		$("#acomm_"+id).append("<span class='click_option' id='aCommCancel_"+id+"' onclick='$(\"#acomm_"+id+"\").remove();'>Cancel</span>");
		$("#aCommSubmit_"+id).click(function() {
			$.ajax({
				type: "POST",
				url: "comment.php",
				data: {
					comm_type: 1,
					comment: $("#comm_"+id).val(),
					link: id
				},
				success: function (data) {
					console.info(data);
					location.reload();
				}
				
			});
		});
	});
	
	$(".btnQComment").click(function() {
		id = $(this).attr('id');
		$(".question").append("<div id='qcomm_"+id+"' class='q_comment'></div>");
		$("#qcomm_"+id).append("<textarea id='comm_"+id+"' placeholder='Type your comment here'></textarea>");
		$("#qcomm_"+id).append("<span class='click_option' id='qCommSubmit'>Submit</span>");
		$("#qcomm_"+id).append("<span class='click_option' id='qCommCancel' onclick='$(\"#qcomm_"+id+"\").remove();'>Cancel</span>");
		
		$("#qCommSubmit").click(function() {
			$.ajax({
				type: "POST",
				url: "comment.php",
				data: {
					comm_type: 0,
					comment: $("#comm_"+id).val(),
					link: id
				},
				success: function (data) {
					console.info(data);
					location.reload();
				}
				
			});
		});
	});
	
	$(".thumb_up").click(function() {
		var id=$(this).attr('id');
		console.info(id);
		$.ajax({
			type: "POST",
			url: "vote.php",
			data: {
				dir: 1,
				answer: id
			},
			success: function (data) {
				console.info(data);
				location.reload();
			}
			
		});
	});
	
	$(".thumb_down").click(function() {
		var id=$(this).attr('id');
		console.info(id);
		$.ajax({
			type: "POST",
			url: "vote.php",
			data: {
				dir: 0,
				answer: id
			},
			success: function (data) {
				console.info(data);
				location.reload();
			}
			
		});
	});
	
});