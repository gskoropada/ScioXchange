/*
 * LOGIN
 * Client side login functions.
 */

var validEmail = false;
var validPassword = false;


function login() {
	
	if(validEmail && validPassword) { 	//If a valid email and a valid password are given, 
										//request login to the server
	
		var loginData = {
				email: $("#inLogEmail").val().trim(),
				pwd: $("#inLogPwd").val().trim()
			};
		
		$.ajax({
			type: 'POST',
			url: 'login.php',
			data: loginData,
			success: function(data) {
				console.info(data);
				if(data==-2 || data==-1) {
					$("#msgArea").html("Invalid email or password");
				} else if (data==1) {
					location.reload();

				}
			}
		});
	}
}

//Displays the login dialog.
function loginDialog() {
	$("body").append("<div id='overlay'></div>")
		.append("<script src='scripts/form_validation.js' class='temp_script'></script>");
	
	$("#overlay").addClass("overlay");
	$("#overlay").append("<div id='loginDialog'></div>");
	$("#loginDialog").addClass("dialog").css("width", "500px")
		.append("<span id='msgArea' class='msg_area'></span>")
		.append("<label>Email: </label><input type='text' name='email' id='inLogEmail'><br>")
		.append("<label>Password: </label><input type='password' name='pwd' id='inLogPwd'><br>")
		.append("<p><span id='pwdRst' class='click_option'>Reset password</span></p>")
		.append("<input type='button' value='Login' id='btnLogin' disabled='true'>")
		.append("<img src='images/close_icon.png' onclick='$(\"#overlay\").remove(); $(\".temp_script\").remove();' class='icon click_option window_icon' />");

	$("#msgArea").css("width","200px");
	
	$("#btnLogin").click(function(){
		login();
	});
	
	$("#inLogEmail").focusout(function(){
		if(!chkEmail($("#inLogEmail").val().trim())) {
			$("#msgArea").text("Invalid email address");
			toggleLoginButton();
		} else {
			$("#msgArea").text("");
			validEmail = true;
			toggleLoginButton();
		}
	});
	
	$("#inLogPwd").keyup(function(){
		if($("#inLogPwd").val().trim()!=""){
			if(!chkPassword($("#inLogPwd").val().trim())) {
				$("#msgArea").text("Password should be 6 to 8 characters long and contain at least one number, one upper case letter and one special character");
				validPassword = false;
				toggleLoginButton();
			} else {
				$("#msgArea").text("");
				validPassword = true;
				toggleLoginButton();
			}
		}
	});
	
	$("#pwdRst").click(function() {
		$("#overlay").remove();
		pwdReset();
	});
	
	$("#inLogEmail").focusout(function() {
	var url = "form_validation.php?q=1&email="+$("#inLogEmail").val().trim();
	$.get(url, function(data) {
		if(data) {
			$("#msgArea").text("");
			validEmail = true;
			toggleLoginButton();
		} else {
			$("#msgArea").text("Email not registered!");
			validEmail = false;
			toggleLoginButton();
		}
	});
});
}

function toggleLoginButton() {
	console.info(validEmail, validPassword);
	if(validEmail && validPassword) {
		$("#btnLogin").prop("disabled",false);
	} else {
		$("#btnLogin").prop("disabled",true);
	}
}

//Displays the password reset dialog
function pwdReset() {
	$("body").append("<div id='overlay'></div>");
	$("#overlay").addClass("overlay");
	$("#overlay").append("<div id='pwdRstDialog'></div>");
	$("#pwdRstDialog").addClass("dialog").css("width","350px")
		.append("<label>Enter your email: </label>")
		.append("<input type='text' name='rstEmail' id='inRstEmail'><br>")
		.append("<input type='button' id='rstSubmit' value='Reset password'>")
		.append("<span id='msgArea' class='msg_area'></span>")
		.append("<img src='images/close_icon.png' onclick='$(\"#overlay\").remove(); $(\".temp_script\").remove();' class='icon click_option window_icon' />");
	
	$("#rstSubmit").click(function() {
		$.ajax({
			type: 'POST',
			url: 'login.php',
			data: {
				pr: true,
				email: $("#inRstEmail").val().trim()
			},
			success: function(data) {
				console.info(data);
				if(data != -1) {
					alert("Your new password is " + data + "\nYou will have to change it the next time you login.");
				}
			}
		});
	});
}