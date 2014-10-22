<?php
require "session.php";
require "connect.php";

if(isset($_POST['action'])) {
	switch($_POST['action']) {
		case "sp":
			showMessage($_POST['id']);
			break;
		case "mr":
			markRead($_POST['id']);
			break;
		case "fl":
			fetchMsgList();
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

function fetchMsgList() {
	global $con;
	
	$query = "SELECT message.message_id as mid, message.sent_by, message.subject, message.reply_to, message_recipient.read as r, message.timestamp, user.screenName, rt_subject, rt_sent_by, rt_screenName, rt_timestamp from
				message inner join message_recipient on message.message_id = message_recipient.message_id
				inner join user on sent_by=UserID
				left join (SELECT message_id, subject as rt_subject, timestamp as rt_timestamp, sent_by as rt_sent_by, screenName as rt_screenName FROM 
							message inner join user on sent_by = UserId) as rt
					on rt.message_id = message.reply_to
				where recipient=".$_SESSION['userid']." order by message.timestamp desc";
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		$msgList = array();
		while($msg = mysqli_fetch_assoc($result)) {
			$msgList[] = $msg; 
		}
		echo json_encode($msgList);
	}
}

function markRead($id) {
	global $con;
	
	$query = "UPDATE message_recipient SET message_recipient.read=1 WHERE message_id=$id AND recipient=".$_SESSION['userid'];
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		echo 1;
	}
}
?>