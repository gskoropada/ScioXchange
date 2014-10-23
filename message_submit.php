<?php
$styles = ["main.css", "header.css"];
$title = "Scio Exchange - Save Question";
include "html_start.php";
include "header.php";

if(!function_exists("notify")) {
	require "notify.php";
}

if(!isset($_POST['submit']))  {//security validation
		echo 'unauthorised access';	
	} else {
	// data entered in all boxes
		$required = array('subject', 'message');
		$error = false;
		foreach($required as $field) {
			if (empty($_POST[$field])) {
				$error = true;
			}
		}
	if ($error) {
		echo "All fields are required.";
	} else {
		$from= $_SESSION['userid'];
		$recipient = $_POST['send_to'];
		$subject = $_POST['subject'];
		$content = $_POST['message'];
		$timestamp = date("c");

		require "connect.php";



	$insert_message = "INSERT INTO message (subject, content, sent_by, timestamp) VALUES ('".mysqli_real_escape_string($con, $subject)."', '".mysqli_real_escape_string($con,$content)."', $from, '$timestamp')";
		
	$message_result = mysqli_query($con, $insert_message);
	
		if($message_result) {			
		
			$select_id = "SELECT message_id FROM message WHERE sent_by =".$from." and timestamp ='".$timestamp."'";
			
			$id_result = mysqli_query($con,$select_id); 
			
	
			if ($id_result) {

				while($message_id = mysqli_fetch_array($id_result)) {
					$id = $message_id['message_id'];
					$insert_recipient = "INSERT INTO message_recipient (message_id, recipient) VALUES ($id,$recipient)";
					
					$recipient_result = mysqli_query($con,$insert_recipient);
					
					}
				
				if ($recipient_result) {
					echo"<p>message sent</p>";
					notify($id,NOT_ORI_MSG,NOT_TYPE_MSG_RECEIVED,$id);
				}else {
					echo " MESSAGE NOT SENT - Recipient update failed.";
					echo mysqli_error($con);
				} 
			} else {
				echo " MESSAGE NOT SENT - Lookup ID failed.";
			}
		} else {
			echo " MESSAGE NOT SENT - Message table entry failed.";
			echo mysqli_error($con);
		}
	}
}
 ?>	