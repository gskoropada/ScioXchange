<?php
/*
 * USER ACTIVATION page.
 * Allows newly registered users to activate their accounts.
 * Users have to enter their activation key and password to become active.
 */

//Varibles for html_start.php
$title = "Scio Exchange - User activation";
$styles = ["main.css", "header.css"];
$scripts = ["jquery-1.11.1.js"];

require("html_start.php");
require('header.php');

//Checks if an activation key has been passd through the GET method, if not, displays a 
// form asking for that information.
if (!isset($_GET['ak'])) {
	echo "<form method='get' action='activate.php'>
<label>Enter your activation key </label><input type='text' name='ak'>
<input type='submit' value='Submit'>
</form>";
} else {
	require_once("connect.php");
	
	$query = "SELECT UserID FROM validation_key WHERE ValidationKey='".$_GET['ak']."'";

	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "Error";
	} else {
		
		$row = mysqli_fetch_array($result);
		
		if(empty($row)) {
			echo "Invalid activation key";
		} else {
				
			echo "<form method='post' action='activate.php?ak=".$_GET['ak']."'>
	<label>Enter your password: </label><input type='password' name='pwd'>
	<input type='submit' value='Submit'>
</form>";
		}
	}
	$con -> close();
}

//Check if the activation key and password are set before proceeding to activate the user.
if(isset($_GET['ak']) && isset($_POST['pwd'])) {
	require("connect.php");
	
	$query = "SELECT user.UserId, pwd from user inner join validation_key 
			on user.UserID = validation_key.UserID
			where ValidationKey = '".$_GET['ak']."'";
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "Error";
	} else {
		$row = mysqli_fetch_array($result);
		
		if($row['pwd']==$_POST['pwd']) {
			
			$query = "UPDATE user 
					SET active = 1
					WHERE UserId=".$row['UserId'].";";
					
			$query1 = "DELETE FROM validation_key 
					WHERE UserId=".$row['UserId'].";";
			
			$result = mysqli_query($con, $query);
			$result1 = mysqli_query($con, $query1);
			
			if(!$result || !$result1) {
				echo "error<br>";
				var_dump($query);
				echo "<br>";
				var_dump($result);
				echo "<br>";
				var_dump($query1);
				echo "<br>";
				var_dump($result1);
			} else {
				echo "User Activated";
			}
			
			$con -> close();
		}
	}
}
require("html_end.php");
?>