<?php
/*
 * ACCOUNT management page.
 * Allows changing the current password and changing the profile picture. 
 */

//variables for html_start.php
$title = "Scio Exchange - Account settings";
$styles = ["main.css","header.css","account.css"];
$scripts = ["jquery-1.11.1.js","form_validation.js", "account.js"];

require('html_start.php');

//If $_POST['pwd'] is set, follow password change process.
if(isset($_POST["pwd"])) {
	changePassword();
}
require('header.php');

//Checks if a session has been started before proceeding.
if(isset($_SESSION['userid'])) {
	require("forms/profile_pic_form.php");
	echo "<h2>".$_SESSION['screenname']."</h2>";
	require("connect.php");
	
	$query = "SELECT active, moderated FROM user WHERE userid=".$_SESSION['userid'];
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "db error";
	} else {
		$status = mysqli_fetch_array($result);
		if(!$status['active']) {
			echo "<p>User not activated</p>";
		} 
		
		if ($status['moderated']) {
			echo "<p>User moderated</p>";
		}
	}
	
	require("forms/account_form.htm");
} else {
	echo "<p>Not logged in!</p>";
}

require('html_end.php');

//Password change process.
function changePassword() {

	$query = "SELECT pwd FROM user WHERE userid='".$_SESSION['userid']."'";
	
	if(!isset($con)) {
		require("connect.php");
	}
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "DB Error";
	} else {
		$pwd = mysqli_fetch_array($result);
		
		if(password_verify($_POST['curPwd'],$pwd[0] )){

			$query = "UPDATE user SET pwd = '" .password_hash($_POST['pwd'], PASSWORD_DEFAULT)."', pwdChange=0 WHERE UserId = ".$_SESSION['userid'];
			
			if(!mysqli_query($con, $query)) {
				echo "DB Error";
			} else {
				echo "<script>alert('Password succesfully changed!');</script>";
				$_SESSION['pwdRst']=0;
			}
			
		} else {
				echo "<script>alert('Incorrect password!');</script>";
		}
	}
	
	
}
?>