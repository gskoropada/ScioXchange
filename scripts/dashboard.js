$(function() {
	
	var id;
	
	$.ajax({ //Asks the server for the logged in user id.
		url: "dashboard_functions.php",
		type: "POST",
		data: {
			action: "id"
		},
		success: function(data) {
			id = parseInt(data);
			$.ajax({ //Asks the server for the question count for the logged in user
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
			
			$.ajax({ //Asks the server for the answer count for the logged in user
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
			
			$.ajax({ //Requests questions from the logged in user
				type: "POST",
				url: "questions_backend.php",
				data: {
					fetch: 10,
					id: id,
					offset: 0
				},
				success: function(data) {
					console.info(JSON.parse(data));
					listQuestions(JSON.parse(data), "#usrQuestions");
				}
			});
			
		}	
	});
	
});
