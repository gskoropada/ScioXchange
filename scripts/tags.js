$(function() {
	
	var root_tags = [];
	
	$.ajax({
		type: 'POST',
		url: 'tag_functions.php',
		data: {
			action: "rt"
		},
		dataType: "json",
		success: function(data) {
			
			root_tags = data;
			
			for(var i=0; i<data.length; i++) {
				console.info(data[i]);
				$("#tree").append("<li>"+data[i].tag+"</li>");
			}
			
			console.info(root_tags);
		}
	});
});