<?php
/*
 * REGISTER
 * Provides user registartion functionality.
 */

//Variables for html_start.php
$title = "Scio Exchange - Registration";
$styles = ["main.css", "header.css"];
$scripts = ["jquery-1.11.1.js", "registration_form.js", "form_validation.js"];

require('html_start.php');
require('header.php');
require('forms/registration_form.htm');

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
				} else {
					echo "User succesfully added";
				}
			} else {
				echo "Error";
			}
						
			$ak = mysqli_fetch_array(
					mysqli_query($con, 
					"SELECT validationkey from validation_key where UserId=".$usrid['UserID']));
			
			$link = "activate.php?ak=".$ak['validationkey'];
			
			$message = actMessage($link, $ak[0]);
				
			@mail($_POST['email'], "User activation", actMessage($link, $ak[0])) or 
				die ($message); //Send by e-mail or displays on screen. (email doesn't work on WAMP)
			
		}
	}
	
	$con->close();

}

require("html_end.php");

//Function that generates the activation e-mail message.
function actMessage($link, $vk) {
	
	$msg ="<h1>Scio Exchange</h1>
	<h2>Your source of all knowledge</h2>
	<p><strong>Congratulations!</strong> You have signed up to the best site on the Web</p>
	<p>To activate your user follow <a href='" . $link . "'>this link</a></p>
	<p>Your activation key is " . $vk ."</p>";
	
	return $msg;
}
?>