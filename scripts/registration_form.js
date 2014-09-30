/*
 * REGISTRATION FORM
 * Client side validation funcitonalities.
 */

var existingEmail = false;
var existingScrname = false;

$(function() {
	$("#btnSubmit").click(function(){
		formSubmit();
	});
	
	$("#inPwd").keyup(function(){
		if(!chkPassword($("#inPwd").val().trim())) {
			$("#chkPwd").text("Password should be 6 to 8 characters long and contain at least one number, one upper case letter and one special character ");
		} else {
			$("#chkPwd").text("");
		}
	});
	
	$("#inScrname").focusout(function() {
		if($("#inScrname").val().trim() == "" || !/[\w\W]{4,25}/.test($("#inScrname").val().trim())){
			$("#chkScrname").text("Invalid Screen name");
		} else {
			$("#chkScrname").text("");
			var url = "form_validation.php?q=1&scrname="+$("#inScrname").val().trim();
			$.get(url, function(data) {
				if(data) {
					$("#chkScrname").text("Screen name already registered!");
					existingScrname = true;
				} else {
					$("#chkScrname").text("");
					existingScrname = false;
				}
			});
		}
	});
	
	$("#inEmail").focusout(function() {
		if(!chkEmail($("#inEmail").val().trim())) {
			$("#chkEmail").text("Invalid email address.");
		} else {
			$("#chkEmail").text("");
			var url = "form_validation.php?q=1&email="+$("#inEmail").val().trim();
			$.get(url, function(data) {
				if(data) {
					$("#chkEmail").text("Email already registered!");
					existingEmail = true;
				} else {
					$("#chkEmail").text("");
					existingEmail = false;
				}
			});
		}
	});
});

//Verifies data validity before submitting to the server.
function formSubmit() {

	$("span").text("");
	
	var validForm = true;
	
	if(!chkEmail($("#inEmail").val().trim()) || existingEmail){
		$("#chkEmail").text("Invalid email address.");
		validForm = false;
	}
	
	if($("#inScrname").val().trim() == "" || existingScrname){
		validForm = false;
		$("#chkScrname").text("Screen Name is a required field!");
	}
	
	if(!chkPassword($("#inPwd").val().trim())) {
			validForm = false;
			$("#chkPwd").text("Password should be 6 to 8 characters long and contain at least one number, one upper case letter and one special character ");
	} 
			
	if(!pwdMatch($("#inPwd").val().trim(),$("#inCpwd").val().trim())) {
		validForm = false;
		$("#chkCpwd").text("Password mismatch!");
	}

	if(validForm) {
		document.forms.registration.submit();
	}
}


