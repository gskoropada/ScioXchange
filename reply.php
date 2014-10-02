<?php
require("connect.php");
require("session.php");

if(isset($_SESSION['userid'])) {
	$query = "INSERT INTO answer (author, content, question, timestamp) VALUES (".$_SESSION['userid'].", '"
			.mysqli_real_escape_string($con, $_POST['answer'])."',".$_POST['qid'].", '".date("c")."')";
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo -1;
	} else {
		echo 1;
	}
}
?>
