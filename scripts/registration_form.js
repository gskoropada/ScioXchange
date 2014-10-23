/*
 * REGISTRATION FORM
 * Client side validation funcitonalities.
 */

var existingEmail = false;
var existingScrname = false;

$(function() {
	$("#inEmail").focus();
	
	$("#btnSubmit").click(function(){
		formSubmit();
	});
	
	$("#inPwd").keyup(function(){
		if(!chkPassword($("#inPwd").val().trim())) {
			if($(".pwd_msg").length > 0) {
				$(".pwd_msg").text("Password should be 6 to 8 characters long and contain at least one number, one upper case letter and one special character");
			} else {
				$("#reg_validation_msg").append("<span class='pwd_msg'>Password should be 6 to 8 characters long and contain at least one number, one upper case letter and one special character</span> ");
				$("#inPwd").addClass("invalid");
			}
		} else {
			$(".pwd_msg").remove();
			$("#inPwd").removeClass("invalid");
		}
	});
	
	$("#inScrname").focusout(function() {
		if($("#inScrname").val().trim() == "" || !/[\w\W]{4,25}/.test($("#inScrname").val().trim())){
			if($(".sn_msg").length >0 ){
				$(".sn_msg").text("Invalid Screen name");
			} else {
				$("#reg_validation_msg").append("<span class='sn_msg'>Invalid Screen name</span>");
			}
			$("#inScrname").addClass("invalid");
		} else {
			$(".sn_msg").remove();
			$("#inScrname").removeClass("invalid");
			var url = "form_validation.php?q=1&scrname="+$("#inScrname").val().trim();
			$.get(url, function(data) {
				if(data) {
					$("#reg_validation_msg").append("<span class='sn_msg'>Screen name already registered!</span>");
					$("#inScrname").addClass("invalid");
					existingScrname = true;
				} else {
					$(".sn_msg").remove();
					$("#inScrname").removeClass("invalid");
					existingScrname = false;
				}
			});
		}
	});
	
	$("#inEmail").focusout(function() {
		if(!chkEmail($("#inEmail").val().trim())) {
			if($(".email_msg").length >0) {
				$(".email_msg").text("Invalid email address.");
			} else {
				$("#reg_validation_msg").append("<span class='email_msg'>Invalid email address.</span>");
			}
			$("#inEmail").addClass("invalid");
		} else {
			$(".email_msg").remove();
			$("#inEmail").addClass("invalid");
			var url = "form_validation.php?q=1&email="+$("#inEmail").val().trim();
			$.get(url, function(data) {
				if(data) {
					$("#reg_validation_msg").append("<span class='email_msg'>Email already registered!</span>");
					$("#inEmail").addClass("invalid");
					existingEmail = true;
				} else {
					$(".email_msg").remove();
					existingEmail = false;
					$("#inEmail").removeClass("invalid");
				}
			});
		}
	});
});

//Verifies data validity before submitting to the server.
function formSubmit() {

	$("#reg_validation_msg > span").remove();
	$(".invalid").removeClass("invalid");
	
	var validForm = true;
	
	if(!chkEmail($("#inEmail").val().trim()) || existingEmail){
		$("#reg_validation_msg").append("<span class='email_msg'>Invalid email address.</span>");
		$("#inEmail").addClass("invalid");
		validForm = false;
	}
	
	if($("#inScrname").val().trim() == "" || existingScrname){
		validForm = false;
		$("#reg_validation_msg").append("<span class='sn_msg'>Screen Name is a required field!</span>");
		$("#inScrname").addClass("invalid");
	}
	
	if(!chkPassword($("#inPwd").val().trim())) {
			validForm = false;
			$("#reg_validation_msg").append("<span class='pwd_msg'>Password should be 6 to 8 characters long and contain at least one number, one upper case letter and one special character</span>");
			$("#inPwd").addClass("invalid");
	} 
			
	if(!pwdMatch($("#inPwd").val().trim(),$("#inCpwd").val().trim())) {
		validForm = false;
		$("#reg_validation_msg").append("<span class='pwd_msg'>Password mismatch!</span>");
		$("#inPwd").addClass("invalid");
		$("#inCpwd").addClass("invalid");
	}

	if(validForm) {
		document.forms.registration.submit();
	}
}


