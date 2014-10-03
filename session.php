<?php
/*
 * SESSION start and timeout procedure.
 * $_SESSION['userid'] Represents the UserID as stored in the Database
 * $_SESSION['screenname'] User's Screen name
 * $_SESSION['role'] Stores user role as defined in the Database
 * $_SESSION['avatar'] Stores a unique identifier refering to the User's picture located in profile_pics\
 * $_SESSION['pwdRst'] Flag indicating that the user must change the password because it has been recently reset;
 */
$timeout = 3600; //Expressed in seconds

session_start();
if(!isset($track_activity)) {
	$track_activity = true;
}
if($track_activity) {
	if(isset($_SESSION['timestamp'])) {
		
		$diff = time() - $_SESSION['timestamp'];
		
		if( $diff > $timeout) {
			session_unset();
			session_destroy();
		} else {
			$_SESSION['timestamp'] = time();
		}
	} else {
		$_SESSION['timestamp'] = time();
	}
	
}
?>
