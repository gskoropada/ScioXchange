<?php
require "session.php";
if(isset($_SESSION['userid'])) {
	require "connect.php";
	if(isset($_POST['answer'])) {
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
		}
	}		
}
?>