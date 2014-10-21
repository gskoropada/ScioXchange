<?php
$title = "Scio Exchange - Inbox";
$styles = ["main.css", "header.css","inbox.css"];
$scripts = ["inbox.js"];
require "html_start.php";
require "header.php";
require "forms/inbox_pane.php";
require "html_end.php";

function listMessages() {
	require "connect.php";
		
	$query = "SELECT message.message_id as mid, sent_by, subject, reply_to, message_recipient.read as r, timestamp, screenName from 
				message inner join message_recipient on message.message_id = message_recipient.message_id
				inner join user on sent_by=UserID
				where recipient=".$_SESSION['userid']." order by timestamp desc";
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		$i=1;
		while($msg = mysqli_fetch_array($result)) {
			echo "<div id='".$msg['mid']."' class='msg_short click_option";
			if($i<0) {
				echo " alt_row";
			}
			echo "'>";
			echo "<span class='msg_subject'>".$msg['subject']."</span>";
			echo "<span class='msg_from'>".$msg['screenName']."</span>";
			echo "<span class='msg_date'>".$msg['timestamp']."</span></div>";
			$i = $i*(-1);
		}
	}
}
?>