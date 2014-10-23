<?php
require "connect.php";

if(!function_exists("notify")) {
	require "notify.php";
}

if(isset($_POST['action'])) {
	switch($_POST['action']) {
		case "sm":
			sendMessage($_POST['msg']);
			break;
	}
}

function escape_characters($str) {
	$chars = [["\\",'\\\\'],["\"",'\\\"'],["\/",'\\\/'],["\n",'\\\n'],["\r",'\\\r'],["\b",'\\\b'],["\t",'\\\t'],["\f",'\\\f']];

	$str_ret = $str;
	
	foreach($chars as $c) {
		$str_ret = str_replace($c[0],$c[1],$str_ret);
	}
	
	return $str_ret;
}

function sendMessage($msgJSON) {
	global $con;
	
	$msg = json_decode($msgJSON);
	
	$timestamp = date("c");
	$insert_message = "INSERT INTO message (subject, content, sent_by, timestamp) VALUES
			 ('".mysqli_real_escape_string($con, $msg->subject)."', '".mysqli_real_escape_string($con,$msg->content)."', $msg->from, '$timestamp')";
	
	$message_result = mysqli_query($con, $insert_message);
	
	if($message_result) {
	
		$select_id = "SELECT message_id FROM message WHERE sent_by =".$msg->from." and timestamp ='".$timestamp."'";
			
		$id_result = mysqli_query($con,$select_id);
	
		if ($id_result) {
	
			while($message_id = mysqli_fetch_array($id_result)) {
				$id = $message_id['message_id'];
				$insert_recipient = "INSERT INTO message_recipient (message_id, recipient) VALUES ($id,$msg->to)";
					
				$recipient_result = mysqli_query($con,$insert_recipient);
					
			}
	
			if ($recipient_result) {
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

?>
