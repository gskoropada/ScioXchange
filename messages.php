<?php
if(isset($_GET['mt'])) {
	switch($_GET['mt']) {
		case "R":
			$msg_form = "forms/registration_success_msg.php";
			break;
		case "AS":
			$msg_form = "forms/activation_success_msg.php";
			break;
	}
}

	$title = "Scio Exchange - Registration Succesful";
	$styles = ["main.css", "header.css"];
	
	require "html_start.php";
	require "header.php";
	require $msg_form;
	require "html_end.php";

?>