<?php
require ("session.php");
if(!function_exists("notify")) {
	require "notify.php";
}

if(isset($_POST['comm_type'])) {
	require ("connect.php");
	
	if(isset($_SESSION['userid'])) {
		$query = "INSERT INTO comment (comment_author, comment, link, link_to, timestamp) VALUES (".$_SESSION['userid'].",'"
				.mysqli_real_escape_string($con, $_POST['comment'])."',".$_POST['link'].",".$_POST['comm_type'].",'".date("c")."')";
		$result = mysqli_query($con, $query);
		
		if(!$result) {
			echo "DB Error";
			echo mysqli_error($con);
			
		} else {
			echo "Comment saved";
			if($_POST['comm_type']==NOT_ORI_ANSWER) {
				$parent = getParent($_POST['link']);
				$usr = getUser($_POST['link'],NOT_ORI_ANSWER);
			} else if($_POST['comm_type']==NOT_ORI_QUESTION) {
				$parent = $_POST['link'];
				$usr = getUser($_POST['link'],NOT_ORI_QUESTION);
			}
			if($usr != $_SESSION['userid']) {
				notify($_POST['link'], $_POST['comm_type'],NOT_TYPE_COMM_RECEIVED, $parent);
			}
		}
	} else {
		echo "Not logged in";
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