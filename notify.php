<?php
/*
 * $origin = ID of the object generating the notification (i.e. question or answer)
 * $type = Type of the object generating the notification:
 * 			0 -> Question
 * 			1 -> Answer
 * $not_type = Type of the event that generated the notification:
 * 			0 -> Answer received
 * 			1 -> Comment received
 * 			2 -> Vote received 
 */

define("NOT_ORI_QUESTION", 0);
define("NOT_ORI_ANSWER",1);
define("NOT_TYPE_ANS_RECEIVED", 0);
define("NOT_TYPE_COMM_RECEIVED", 1);
define("NOT_TYPE_VOTE_RECEIVED", 2);

if(isset($_POST['get_notifications'])) {
	if($_POST['get_notifications'] == 1) {
		fetchNotifications();
	}
}

if(isset($_POST['ack'])) {
	if($_POST['ack']==1) {
		ackNotifications();
	}
}

if(isset($_POST['count'])) {
	if($_POST['count']==1) {
		$track_activity = false;
		require "session.php";
		if(isset($_SESSION['userid'])) {
			echo hasNotifications($_SESSION['userid']);
		}
	}
}

function notify($origin, $type ,$not_type, $parent) {
	require "connect.php";
	$query = "INSERT INTO notification (user, not_type, origin, origin_type, parent, timestamp)
			 VALUES (".getUser($origin, $type).", $not_type, $origin, $type, $parent, '".date("c")."')";
	$result = mysqli_query($con, $query);

	if(!$result) {
		echo mysqli_error($con);
	}
}

function getUser($origin, $type) {
	require "connect.php";
	switch($type) {
		case NOT_ORI_QUESTION: //Question
			$query = "SELECT author FROM question WHERE question_id = $origin";
			break;
		case NOT_ORI_ANSWER: //Answer
			$query = "SELECT author FROM answer WHERE answer_id = $origin";
			break;
	}
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
		return false;
	} else {
		$user = mysqli_fetch_array($result);
		return $user[0];
	}
}

function hasNotifications($user) {
	require "connect.php";
	$query = "SELECT count(not_id) FROM notification WHERE user = $user and status=0";
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
		return 0;
	} else {
		$nots = mysqli_fetch_array($result);
		return $nots[0];
	}	
}

function fetchNotifications() {
	require "connect.php";
	require "session.php";
	if(isset($_SESSION['userid'])) {
		$query = "SELECT not_id, origin, origin_type, not_type, timestamp, parent, status FROM notification WHERE user = ".$_SESSION['userid']." ORDER BY timestamp DESC LIMIT 20";
		$result = mysqli_query($con, $query);
		if(!$result) {
			echo mysqli_error($con);
		} else {
			$notifications = array();
			while ($not = mysqli_fetch_assoc($result)){
				$notifications[] = $not;
			}
			echo json_encode($notifications);
		}
	}
}

function ackNotifications() {
	require "connect.php";
	require "session.php";
	if(isset($_SESSION['userid'])) {
		$query = "UPDATE notification SET status = 1 WHERE user=".$_SESSION['userid']." AND timestamp < '".date("c")."' AND NOT status";
		$result = mysqli_query($con, $query);
		if(!$result) {
			echo mysqli_error($con);
		} else {
			echo "ACK";
			
		}
	}
}

?>