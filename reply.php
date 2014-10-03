<?php
require("connect.php");
require("session.php");
if(!function_exists("notify")) {
	require "notify.php";
}

if(isset($_SESSION['userid'])) {
	$timestamp = date("c");
	$query = "INSERT INTO answer (author, content, question, timestamp) VALUES (".$_SESSION['userid'].", '"
			.mysqli_real_escape_string($con, $_POST['answer'])."',".$_POST['qid'].", '$timestamp')";
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo -1;
	} else {
		echo 1;
		$query = "SELECT answer_id FROM answer WHERE question=".$_POST['qid']." AND author=".$_SESSION['userid'].
				" AND timestamp = '$timestamp'";
		$result = mysqli_query($con, $query);
		if(!$result) {
			echo mysqli_error($con);
		} else {
			$parent = mysqli_fetch_assoc($result);
			notify($_POST['qid'], NOT_ORI_QUESTION, NOT_TYPE_ANS_RECEIVED, $parent['answer_id']);
		}
	}
}
?>
