<?php
if (session_status() == PHP_SESSION_NONE) {
	require "session.php";
}
if(isset($_SESSION['userid'])) {
	require "connect.php";
	if(!function_exists("notify")) {
		require "notify.php";
	}
	if(isset($_POST['answer'])) {
		if(checkVote($_POST['answer'])) {
			if($_POST['dir']>0) {
				$vote = "positive_votes";
			} else {
				$vote = "negative_votes";
			}
			$query = "update answer set $vote = $vote + 1 where answer_id = ".$_POST['answer'];
			$result = mysqli_query($con,$query);
			if(!$result) {
				echo "DB Error";
				echo mysqli_error($con);
			} else {
				echo $vote;
				trackVote();
				notify($_POST['answer'], NOT_ORI_ANSWER, NOT_TYPE_VOTE_RECEIVED, getParent($_POST['answer']));
			}
		} else {
			echo "already voted";
		}
	}		
}

function trackVote() {
	global $con;
	$query = "INSERT INTO vote_tracking (author, answer, timestamp) VALUES (".$_SESSION['userid'].",".$_POST['answer'].",'".date("c")."')";
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "DB error";
		echo mysqli_error($con);
	} else {
		echo "Vote tracked";
	}
}

function checkVote($id) {
	global $con;
	$query = "SELECT count(answer) as voted FROM vote_tracking WHERE author=".$_SESSION['userid']." AND answer=".$id;
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "DB Error";
	} else{
		$voted = mysqli_fetch_array($result);
		if($voted['voted'] == 0) {
			return true;
		} else {
			return false;
		}
	}
}

function getParent($child) {
	global $con;
	$query = "SELECT question FROM answer WHERE answer_id=$child";
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		$parent = mysqli_fetch_array($result);
		return $parent[0];
	}
}
?>