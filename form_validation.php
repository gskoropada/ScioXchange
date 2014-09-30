<?php
/*
 * FORM VALIDATION
 * Provides back-end functionality for the clients AJAX requests.
 */

if (isset($_GET['q']) && isset($_GET['email'])){
	require_once 'connect.php';
	echo email_exists($_GET['email'], $con);
} elseif (isset($_GET['q']) && isset($_GET['scrname'])){
	require_once 'connect.php';
	echo scrname_exists($_GET['scrname'], $con);
}

//Returns TRUE if the combination of email and screen name exists in the database.
function user_exists($email, $scrname, $con) {
	$exists = false;
		
	if (email_exists($email, $con)) {
		$exists = true;
	}
	
	if (scrname_exists($scrname, $con)) {
		$exists = true;
	}
	
	return $exists;
}

//Returns TRUE if the email exists in the database
function email_exists($email, $con) {
	$exists = false;
	
	$query = "SELECT * from user where email='".$email."'";
	$result = mysqli_query($con, $query);
	
	if (mysqli_num_rows($result)==1) {
		$exists = true;
	}
	
	return $exists;
}

//Returns TRUE if the screen name exists in the database.
function scrname_exists($scrname, $con){
	$exists = false;
	
	$query = "SELECT * from user where screenName='".$scrname."'";
	$result = mysqli_query($con, $query);
	
	if (mysqli_num_rows($result)==1) {
		$exists = true;
	}
	
	return $exists;
}

?>