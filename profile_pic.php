<?php
/*
 * PROFILE PICTURE management
 * Provides back-end functionality for the profile picture options in account.php
 */

require("session.php");

	if(isset($_SESSION['userid'])) {
	
		if(isset($_POST['action'])) {
		switch($_POST['action']) {
			case "up":
				uploadPicture();
				break;
			case "save":
				savePicture();
			default:
				break;
		}
	}
}

//Picture upload function
function uploadPicture() {
	$rnd = rand(1000, 9999);
	
	foreach(glob("profile_pics/*_".$_SESSION['userid']."_temp.jpg") as $filename) {
		unlink($filename);
	}
	
	
	$dest = "profile_pics/" . $rnd . "_" . $_SESSION['userid'] . "_temp.jpg";
	
	$pic = imagecreatetruecolor(250, 250);
	$source = imagecreatefromjpeg($_FILES["pic"]["tmp_name"]);
	$sourceWidth = getimagesize($_FILES["pic"]["tmp_name"])[0];
	$sourceHeight = getimagesize($_FILES["pic"]["tmp_name"])[1];
		
	if($sourceWidth>$sourceHeight) {
		$sourceWidth = $sourceHeight;
	} else if ($sourceHeight > $sourceWidth) {
		$sourceHeight = $sourceWidth;
	}
	
	imagecopyresampled($pic, $source, 0, 0, 0, 0, 250, 250, $sourceWidth, $sourceHeight);
	
	imagejpeg($pic, $dest);
	
	echo $dest;
}

//Picture save option.
function savePicture() {
		
	$origin = glob("profile_pics/*_".$_SESSION['userid']."_temp.jpg");
	$dest = "profile_pics/" . $_SESSION['avatar'] . ".jpg";
	
	if(rename($origin[0], $dest)) {

		$thumb = imagecreatetruecolor(45,45);
		$source = imagecreatefromjpeg($dest);
		$sourceWidth = getimagesize($dest)[0];
		$sourceHeight = getimagesize($dest)[1];
		imagecopyresampled($thumb, $source, 0,0,0,0,45,45, $sourceWidth, $sourceHeight);
		
		imagejpeg($thumb, "profile_pics/thumbs/".$_SESSION['avatar'].".jpg");
		
		echo "OK";
		
	} else {
		echo "ERROR";
	}
}

?>