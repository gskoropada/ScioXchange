<?php
require "session.php";
require "connect.php";

if(isset($_POST['action'])) {
	switch($_POST['action']) {
		case "sp": //Show preview
			showMessage($_POST['id']);
			break;
		case "mr": //Mark read
			markRead($_POST['id']);
			break;
		case "fl": //Fetch list
			fetchMsgList(false);
			break;
		case "fls": //Fetch list sent
			fetchMsgList(true);
			break;
		case "mc": //Message count
			getMessageCount($_SESSION['userid'],"all");
			break;
		case "mcu": //Message count unread
			getMessageCount($_SESSION['userid'],"unread");
			break;
		case "mcs": //Message count sent
			getMessageCount($_SESSION['userid'],"sent");
			break;
	}
}

function showMessage($id) { //Sends the data for a specific message to the client.
	global $con;
	
	$query = "SELECT message.message_id as mid, subject, content, recipient, message_recipient.read as r, timestamp, sent_by, user.screenName, r_user.screenName as r_screenName
			FROM message INNER JOIN message_recipient ON message.message_id = message_recipient.message_id
			INNER JOIN user ON sent_by = user.UserID 
			inner join user as r_user on recipient = r_user.UserId
			WHERE message.message_id = $id";
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		echo json_encode(mysqli_fetch_assoc($result));
	}
}

function fetchMsgList($sent) { //Sends the complete list of messages to the client.
	global $con;
	
	if($sent) {
		$query = "SELECT message.message_id as mid, message.sent_by, message.subject, message.reply_to, message_recipient.read as r, message.timestamp, user.screenName, rt_subject, rt_sent_by, rt_screenName, rt_timestamp from
				message inner join message_recipient on message.message_id = message_recipient.message_id
				inner join user on recipient=UserID
				left join (SELECT message_id, subject as rt_subject, timestamp as rt_timestamp, sent_by as rt_sent_by, screenName as rt_screenName FROM
							message inner join user on sent_by = UserId) as rt
					on rt.message_id = message.reply_to
				where message.sent_by=".$_SESSION['userid']." order by message.timestamp desc";
	} else {
		$query = "SELECT message.message_id as mid, message.sent_by, message.subject, message.reply_to, message_recipient.read as r, message.timestamp, user.screenName, rt_subject, rt_sent_by, rt_screenName, rt_timestamp from
				message inner join message_recipient on message.message_id = message_recipient.message_id
				inner join user on sent_by=UserID
				left join (SELECT message_id, subject as rt_subject, timestamp as rt_timestamp, sent_by as rt_sent_by, screenName as rt_screenName FROM 
							message inner join user on sent_by = UserId) as rt
					on rt.message_id = message.reply_to
				where recipient=".$_SESSION['userid']." order by message.timestamp desc";
	}
	
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

function markRead($id) { //Marks a specific message as read.
	global $con;
	
	$query = "UPDATE message_recipient SET message_recipient.read=1 WHERE message_id=$id AND recipient=".$_SESSION['userid'];
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		echo 1;
	}
}

function getMessageCount($id, $scope) { //Sends the requested count to the client.
	global $con;
	
	switch($scope) {
		case "all":
			$query = "SELECT count(recipient) as c FROM message_recipient WHERE recipient = $id";
			break;
		case "unread":
			$query = "SELECT count(recipient) as c FROM message_recipient WHERE recipient = $id AND NOT message_recipient.read";
			break;
		case "sent":
			$query = "SELECT count(sent_by) as c FROM message WHERE sent_by=$id";
			break;
	}
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		$count = mysqli_fetch_array($result);
		
		echo $count['c'];
	}
}
?>