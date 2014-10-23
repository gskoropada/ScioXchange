<?php
/*
 * $origin = ID of the object generating the notification (i.e. question or answer)
 * $type = Type of the object generating the notification:
 * 			0 -> Question
 * 			1 -> Answer
 * 			2 -> Message
 * $not_type = Type of the event that generated the notification:
 * 			0 -> Answer received
 * 			1 -> Comment received
 * 			2 -> Vote received 
 * 			3 -> Message received
 */

define("NOT_ORI_QUESTION", 0);
define("NOT_ORI_ANSWER",1);
define("NOT_ORI_MSG",2);

define("NOT_TYPE_ANS_RECEIVED", 0);
define("NOT_TYPE_COMM_RECEIVED", 1);
define("NOT_TYPE_VOTE_RECEIVED", 2);
define("NOT_TYPE_MSG_RECEIVED", 3);

if(isset($_POST['get_notifications'])) {
	if($_POST['get_notifications'] == 1) {
		fetchNotifications();
	}
}

if(isset($_POST['ack'])) { //Acknowled the unread notifications.
	if($_POST['ack']==1) {
		ackNotifications();
	}
}

if(isset($_POST['count'])) {
	if($_POST['count']==1) {
		$track_activity = false; //Flag for session.php
		require "session.php";
		if(isset($_SESSION['userid'])) {
			echo hasNotifications($_SESSION['userid']);
		}
	}
}

/*
 * Creates a notification entry according to the following parameters:
 * $origin: ID of the object originating the notification
 * $type: type of the object generating the notifcations. Possible values: NOT_ORI_QUESTION or NOT_ORI_ANSWER
 * $not_type: type of notification generating event. Possible values: NOT_TYPE_ANS_RECEIVED, NOT_TYPE_COMM_RECEIVED, NOT_TYPE_VOTE_RECEIVED
 * $parent: ID of the object that would be linked when the notification is displayed.
 */
function notify($origin, $type ,$not_type, $parent) {
	require "connect.php";
	$usr = getUser($origin, $type);
	$query = "INSERT INTO notification (user, not_type, origin, origin_type, parent, timestamp)
			 VALUES ($usr, $not_type, $origin, $type, $parent, '".date("c")."')";
	$result = mysqli_query($con, $query);

	if(!$result) {
		echo mysqli_error($con);
	}
	
	if($type == NOT_ORI_ANSWER && $not_type == NOT_TYPE_VOTE_RECEIVED) {
		updateReputation(getUser($origin, $type));
	} else if ($type == NOT_ORI_QUESTION && $not_type == NOT_TYPE_ANS_RECEIVED) {
		updateReputation($_SESSION['userid']);
	}
}

// Returns the author for a given origin object of the specified type
function getUser($origin, $type) {
	require "connect.php";
	switch($type) {
		case NOT_ORI_QUESTION: //Question
			$query = "SELECT author FROM question WHERE question_id = $origin";
			break;
		case NOT_ORI_ANSWER: //Answer
			$query = "SELECT author FROM answer WHERE answer_id = $origin";
			break;
		case NOT_ORI_MSG: //Message
			$query = "SELECT recipient FROM message_recipient WHERE message_id = $origin";
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

// Returns the number of unread notification a user has.
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

// Returns notifications to the client encoded in a JSON object.
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

// Acknowledges unread notifications.
function ackNotifications() {
	require "connect.php";
	require "session.php";
	if(isset($_SESSION['userid'])) {
		$query = "UPDATE notification SET status = 1 WHERE user=".$_SESSION['userid']." AND timestamp < '".date("c")."' AND NOT status";
		// the timestamp field is used to filter notifications that may have been created after the query was sent to the server.
		$result = mysqli_query($con, $query);
		if(!$result) {
			echo mysqli_error($con);
		} else {
			echo "ACK";
			
		}
	}
}

// Updates the user reputation. Included in this file as notification generating events are also reputation changing events.
function updateReputation($user) {
	global $con;
	$query = "select (count(answer_id)+(sum(positive_votes) - sum(negative_votes))*2)/2 as reputation from answer where author = $user;";
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		$reputation = mysqli_fetch_array($result);
		$rep = $reputation['reputation'];
		echo "$user: rep=$rep";
		$query = "UPDATE user SET reputation = $rep WHERE UserID = $user";
		$result = mysqli_query($con, $query);
		
		if(!$result) {
			echo mysqli_error($con);
		} 
	}
	
}
?>