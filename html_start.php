<!DOCTYPE=html>
<html lang="en">
<head>
	<?php
	
	//Set page title
	if(isset($title)) {
		echo "<title>".$title."</title>"; 
	}
	?>
	<meta charset="utf-8">
	<?php
	
	//Include stylesheets
	if(isset($styles)) {
		foreach($styles as $style) {
			echo "<link rel='stylesheet' href='styles/".$style."' />";
		}	
	}
	?>
</head>
<body>
<script src='scripts/jquery-1.11.1.js'></script>
<script src='scripts/notifications.js'></script>
<?php
	//Include required scripts
	if(isset($scripts)) {
		foreach($scripts as $script) {
			echo "<script src='scripts/".$script."'></script>";
		}	
	}
	
	//Start session
	require("session.php");
	
	//Include login.js scrips if user is not logged in
	if(!isset($_SESSION['userid'])) {
		echo "<script src='scripts/login.js'></script>";
	}
?>
<div id="container">
<!-- START DYNAMIC CONTENT -->
