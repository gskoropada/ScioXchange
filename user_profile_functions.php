<?php
require "connect.php";

function getQuestionCount($id) {
	global $con;
	
	$query = "SELECT count(question_id) as qCount from question where author=".$id;
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "DB Error";
	} else {
		$qCount = mysqli_fetch_array($result);
		
		echo $qCount['qCount'];
	}

}

function getAnswersCount($id) {
	global $con;
	
	$query = "select count(answer_id) as aCount from answer where question in (
				select question_id from question where author=$id)";
	
	$result = mysqli_query($con,$query);
	
	if(!$result) {
		echo "DB Error";
	} else {
		$aCount = mysqli_fetch_array($result);
		
		echo $aCount['aCount'];
	}
	
}

function screenNameFromUserID($id) {
	global $con;
	
	$query = "select screenName FROM user WHERE UserID = $id";
	$result = mysqli_query($con,$query);
	
		if(!$result) {
		echo "DB Error";
	} else {
		$screenName =  mysqli_fetch_array($result);
		
		echo $screenName['screenName'];
		}
	}
?>