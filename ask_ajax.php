<?php

require "session.php";
require "connect.php";
require "tag_functions.php";

if(!isset($_SESSION['userid'])) {
	echo "Not logged in";
	exit;
}

if(isset($_POST['action'])) {
	switch($_POST['action']) {
		case "sq":
			saveQuestion($_POST['title'], $_POST['question'], $_POST['tags']);
	}
}

function saveQuestion($title, $quest, $tags) {
	$author = $_SESSION['userid'];
	
	global $con;
	
	$sql = "INSERT INTO question (author, question_title, content, tags) values ('$author', '$title', '$quest', '$tags')";
	
	$result = mysqli_query($con, $sql);
	
	if(!$result) {
		echo false;
	} else {
		echo true;
		processTags($tags);
	}
}

function processTags($tags) {
	global $con;
	$tagArray = explode(",", $tags);
	foreach($tagArray as $t) {
		$t = trim($t);
		$count = getTagCount($t);
		if(!$count) {
			$sql = "INSERT INTO tag (tag) values ('$t')";
			$result = mysqli_query($con, $sql);
			if(!$result) {
				echo "DB Error";
			}
		} else {
			$sql = "UPDATE tag SET tag_count = ".++$count." WHERE tag = '$t'";
			$result = mysqli_query($con, $sql);
			if(!$result) {
				echo "DB Error";
			}
		}
	}
}
?>