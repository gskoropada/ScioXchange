<?php
require "session.php";
require "connect.php";

if(isset($_POST['action'])) {
	switch($_POST['action']) {
		case "sp":
			showMessage($_POST['id']);
			break;
	}
}

function showMessage($id) {
	global $con;
	
	$query = "SELECT message.message_id as mid, subject, content, message_recipient.read as r, timestamp, sent_by, screenName
			FROM message INNER JOIN message_recipient ON message.message_id = message_recipient.message_id
			INNER JOIN user ON sent_by = UserID WHERE message.message_id = $id";
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		echo json_encode(mysqli_fetch_assoc($result));
	}
}
?>