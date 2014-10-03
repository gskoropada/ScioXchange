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

function notify($origin, $type ,$not_type) {
	require "connect.php";
	$query = "INSERT INTO notification (user, not_type, origin, origin_type, timestamp)
			 VALUES (".getUser($origin, $type).", $not_type, $origin, $type, '".date("c")."')";
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
?>