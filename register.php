<?php
/*
 * REGISTER
 * Provides user registartion functionality.
 */

require_once "scripts/passwordLib.php";
require "msg_functions.php";

//Variables for html_start.php
$title = "Scio Exchange - Registration";
$styles = ["main.css", "header.css","registration.css"];
$scripts = [ "registration_form.js", "form_validation.js"];

require('html_start.php');
require('header.php');
if(isset($_SESSION['userid'])) {
	echo "You are already registered!";
} else {
	require('forms/registration_form.htm');
}
//Checks if data is passed to continue with the registration process.
if(!empty($_POST)) {
	require('connect.php');
	require('form_validation.php');

	if(!user_exists($_POST['email'], $_POST['scrname'],$con)) {
		$valid = true; //If user doesn't exist
	} else {
		echo "<script>alert('User already exists');</script>";
		$valid = false;
	}
	
	if($valid) {
		
		$avatar = str_shuffle(uniqid());
		
		$pwd_hash = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
		
		$query = "INSERT INTO user (email, screenName, pwd, avatar) values ('";
		$query .= htmlspecialchars($_POST['email']."','".$_POST['scrname']."','".$pwd_hash."','".$avatar."')");
		
		$result = mysqli_query($con, $query);
			
		if(!$result) {
			echo "Could not write to the database";
		} else {
			
			$query = "SELECT UserID FROM user WHERE email = '".$_POST['email']."'";
				
			$usrid = mysqli_fetch_array(mysqli_query($con, $query));
			
			if(isset($usrid['UserID'])) {
			
				$result = mysqli_query($con,"INSERT INTO validation_key (UserID, ValidationKey, TimeStamp) values (".$usrid['UserID'].",'".uniqid("",true)."','".date("c")."')");
				
				if(!$result) {
					echo "Could not write to the database - vk";
				}
			} else {
				echo "Error";
			}
						
			$ak = mysqli_fetch_array(
					mysqli_query($con, 
					"SELECT validationkey from validation_key where UserId=".$usrid['UserID']));
			
			$link = "http://".$_SERVER['HTTP_HOST']."/activate.php?ak=".$ak['validationkey'];
			
			$message = actMessage($link, $ak[0]);
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ScioXchange <activation@scioxchange.com>' . "\r\n";
			
			@mail($_POST['email'], "User activation", actMessage($link, $ak[0]), $headers);
			
			$msgJSON = "{\"to\":".$usrid['UserID'].",\"from\":58,\"subject\":\"Welcome to Scio Exchange!\",\"content\":\"".escape_characters("Check your email for the activation email and start enjoying the site!\nThe Scio Exchange Team")."\" }";
			
			sendMessage($msgJSON);
			
			//Shows an end of process message to the user.
			echo "<script>location.assign('messages.php?mt=R');</script>";
		}
	}
	
	$con->close();

}

require("html_end.php");

//Function that generates the activation e-mail message.
function actMessage($link, $vk) {
	
	$msg ="<html>
			<body>
			<h1>Scio Exchange</h1>
			<h2>Your source of all knowledge</h2>
			<p><strong>Congratulations!</strong> You have signed up to the best site on the Web</p>
			<p>To activate your user follow <a href='" . $link . "'>this link</a></p>
			<p>Your activation key is " . $vk ."</p>
			<p><br/><strong>ScioXchange Team</strong></p>
			</body></html>";
	
	return $msg;
}
?>