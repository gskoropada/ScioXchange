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
			
		}	
	});
	
	
	
});