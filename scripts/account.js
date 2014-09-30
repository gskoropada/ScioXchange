/*
 * ACCOUNT page
 * Client side functions.
 */
var validPassword=false;
var passwordMatch=false;

$(function(){
	
	//Uploads the profile picture when a file is selected.
	$("#inPic").change(function() {
		
		var ext = $(":input[name='pic']").val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['jpg','jpeg']) == -1) {
		    alert('invalid extension!');
		} else {
		
			$(":input[name='action']").val("up");
		
		    var formData = new FormData(document.forms.profile_pic);

	        $.ajax({
	            type:'POST',
	            url: 'profile_pic.php',
	            data: formData,
	            cache:false,
	            contentType: false,
	            processData: false,
	            success:function(data){
	                console.log("success");
	                console.log(data);
	                
	                $("#avatar").prop("src", data);
	                $(":input[name='pic']").val("");
	                $("#btnPicSave").html("<p>Save picture...</p>");
	                $("#btnPicSave").addClass("click_option");
	                	                
	            },
	            error: function(data){
	                console.log("error");
	                console.log(data);
	            }
	        });
		}	
	});
	
	//Sets the recently upload picture as the user profile picture.
	$("#btnPicSave").click(function() {

		var data = {
			action: "save"
		};
		
		$.ajax({
			type: "POST",
			data: data,
			url: "profile_pic.php",
			success: function(data) {
				console.info(data);
				
				var currentThumb = $("#avatar_thumb").prop("src");
				var newThumb = currentThumb+"?"+new Date().getTime();
				$("#avatar_thumb").prop("src", newThumb);
				$("#btnPicSave").html("");
			} 
		});
		
	});
	
	$("#inCurPwd").keyup(function() {
		$("#msgArea").html("");
		if($("#inCurPwd").val()!=""){
			if(!chkPassword($("#inCurPwd").val())) {
				$("#msgArea").html("Invalid password! ");
				validPassword = false;
				toggleButton();
			} else {
				$("#msgArea").html("");
				validPassword = true;
				toggleButton();
			}
		}
	});
	
	$("#inPwd").keyup(function() {
		$("#msgArea").html("");
		if($("#inPwd").val()!=""){
			if(!chkPassword($("#inPwd").val())) {
				$("#msgArea").html("Invalid password! ");
				validPassword = false;
				toggleButton();
			} else {
				$("#msgArea").html("");
				validPassword = true;
				toggleButton();
			}
		}
	});
		
	$("#inCpwd").keyup(function() {
		$("#msgArea").html("");
		if($("#inPwd").val() != $("#inCpwd").val()) {
			$("#msgArea").html("Password mismatch");
			passwordMatch = false;
			toggleButton();
		} else {
			$("#msgArea").html("");
			passwordMatch = true;
			toggleButton();
		}
	});
	
	$("#btnSubmit").click(function() {
		formSubmit();
	});
});

function formSubmit() {
	document.forms.account.submit();
}

function toggleButton() {
	if(passwordMatch && validPassword) {
		$("#btnSubmit").prop("disabled",false);
	} else {
		$("#btnSubmit").prop("disabled",true);
	}
}