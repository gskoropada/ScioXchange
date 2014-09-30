/*
 * FORM VALIDATION functions
 * Client side form validation.
 */

//Checks if the password complies with complexity rules.
function chkPassword(pwd) {
	
	var valid = true;
	
	if(!/([\w\W]{6,20})/.test(pwd) || !(/([\W]{1,})/.test(pwd) && /([A-Z]{1,})/.test(pwd) && /([\d]{1,})/.test(pwd))) {
			valid = false;
	}
	
	return valid;
}

//Checks if the eml parameter is a valid email address.
function chkEmail(eml) {
	var valid=true;
	
	if(eml == ""){
		valid = false;
	} else {
		
		if(!/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(eml)) {
			valid = false;
		}
	}
	
	return valid;
}

//Checks if two passwords match.
function pwdMatch(pwd, pwd1) {
	
	if (pwd != pwd1) {
		var match = false;
	} else {
		match = true;
	}; 

	return match;
}
