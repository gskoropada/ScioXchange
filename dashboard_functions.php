<?php
require "connect.php";
require "session.php";

if(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case "qc":
			getQuestionCount($_POST['id']);
			break;
		case "ac":
			getAnswersCount($_REQUEST['id']);
			break;
		case "id":
			echo $_SESSION['userid'];
			break;
	}
}

function getQuestionCount($id) {
	global $con;
	
	$query = "SELECT count(question_id) as qCount from question where author=".$_SESSION["userid"];
	
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
				select question_id from question where author=".$_SESSION['userid'].")";
	
	$result = mysqli_query($con,$query);
	
	if(!$result) {
		echo "DB Error";
	} else {
		$aCount = mysqli_fetch_array($result);
		
		echo $aCount['aCount'];
	}
	
}
?>