<?php
$title = "Scio Exchange - Inbox";
$styles = ["main.css", "header.css","inbox.css"];
$scripts = ["inbox.js"];
require "html_start.php";
require "header.php";
if(isset($_GET['sent'])) {
	if($_GET['sent']==1) {
		echo "<span class='sent_msgs'></span>"; //Flag for the client side script. Used to show sent messages.
	}
}

if(isset($_GET['goto'])) {
	echo "<span class='goto' name='".$_GET['goto']."'></span>"; //Flag for the client side script. Used to point to a specific message.
}
require "forms/inbox_pane.php";
require "html_end.php";

?>