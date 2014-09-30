<?php
/*
 * ADMINISTRATION page
 * Allows user with administrative privileges to manage the site.
 */

//Variables for html_start.php
$title = "Scio Exchange - Administration tools";
$styles = ["main.css", "header.css", "admin.css"];
$scripts = ["jquery-1.11.1.js", "admin_tools.js"];

require('html_start.php');
require('header.php');

//If a session is active check that the user has the adequate privileges.
if(isset($_SESSION['role'])) {
	require('admin_tools.php');
	require("connect.php");
	if (!($_SESSION['role']==2 && chkAdmin($con))) {
		echo "Not enough privileges";
	} else {
		require('forms/admin_sidebar.php');
		if(isset($_REQUEST['opt'])) {
			//Checks for different administration options.
			switch($_REQUEST['opt']) {
				case "qa":
					echo "Questions admin"; //Yet to be implemented
					break;
				case "ms":
					echo "Messaging services"; //Yet to be implemented
					break;
				case "sm":
					echo "Site maintenance"; //Yet to be implemented
					break;
				default:
					echo "Invalid option";
					break;
			}
		} else {
			require("forms/admin_pane.php");
		}
	}
} else {
	echo "Not logged in";
}

require ('html_end.php');
?>
