<?php
require ("session.php");

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
		}
	} else {
		echo "Not logged in";
	}
}

?>