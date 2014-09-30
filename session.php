<?php
/*
 * SESSION start and timeout procedure.
 */
$timeout = 3600; //Expressed in seconds

session_start();
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

?>
