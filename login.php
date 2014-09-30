<?php
/*
 * LOGIN Functions
 * Provides back-end functionality for the login and password reset options.
 */

//If an email has been passed, perform login process.
if(isset($_POST['email'])) {
	require("connect.php");
	
	//If password reset option has been chosen, perform that process
	if(isset($_POST['pr'])) {
		pwdReset();
	} else {
	
	$query = "SELECT pwd, userid, screenname, role, avatar, pwdChange FROM user WHERE email='".$_POST['email']."'";

	$result = mysqli_query($con, $query);
	
	if(!$result || empty($_POST['email'])) {
		echo -1;
	} else {
		$usr = mysqli_fetch_array($result);
		
		require("session.php");
		
		if (password_verify($_POST['pwd'], $usr['pwd']) && !empty($usr['pwd'])) {
			$_SESSION['userid']=$usr['userid'];
			$_SESSION['screenname']=$usr['screenname'];
			$_SESSION['role']=$usr['role'];
			$_SESSION['avatar']=$usr['avatar'];
			$_SESSION['pwdRst']=$usr['pwdChange'];
			
			echo 1;
		} else {
			echo -2;
		}
	}
	}
	$con->close();
}

//Password reset process.
function pwdReset() {
	
	if(!isset($con)){
		require("connect.php");
	}
	
	$query = "SELECT UserId FROM user WHERE email='".$_POST['email']."'";
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "DB Error";
	} else {
		$newPwd = "A" . substr(str_shuffle(uniqid()),0,6)."%";
		$usr = mysqli_fetch_array($result);
		
		$query = "UPDATE user SET pwd='".password_hash($newPwd, PASSWORD_DEFAULT)."',
				pwdChange=1 WHERE UserId=".$usr[0];
		
		$result = mysqli_query($con, $query);
		
		if(!$result) {
			echo -1;
		} else {
			echo $newPwd;
		}
		
		
	
	}
} 
?>