<?php 
/*
 * HEADER section
 * Provides an integrated navigation menu accross the entire site.
 */

//Check if the user should be redirected in case is not logged in.
if((!isset($_SESSION['userid'])) && indexRedirect()) {
	header("Location: index.php");
	die();
} 

?>

<div id="header">
<div id="logo">
	<h1><a href="index.php">Scio Exchange</a></h1>
	<p>Your source of all knowledge</p>
</div>
<div id="status">
<div id="notifications" class="hidden">
	
</div>
<?php

//Session aware menu options.
if(!isset($_SESSION['userid'])){
	echo "<p><span class='click_option' onclick='loginDialog();'>Login</span> | ";
	echo "<a href='register.php'>Sign Up</a></p>";
	echo "<p><span class='click_option' onclick='pwdReset();'>Forgot your password?</span></p>";
} else {
	require "connect.php";
	if(!function_exists("notify")) {
		require "notify.php";
	}
	$nots = hasNotifications($_SESSION['userid']);
	echo "<p><div class='avatar'><img id='avatar_thumb' src='profile_pics/thumbs/";
	if(!file_exists("profile_pics/thumbs/".$_SESSION['avatar'].".jpg")) {
		echo "default.png'";
	} else {
		echo $_SESSION['avatar'].".jpg'"; 
	}
	echo "></div>";
	
	echo "<span id='not_counter' class='click_option'>$nots</span>";
	
	echo "<div ";
	if(!$_SESSION['active']) {
		echo " class='not_active' title='User not active'";
	}
	echo "><a href='account.php'>".$_SESSION['screenname']."(".$_SESSION['reputation'].")</a>";
	echo " | <a href='logout.php'>Logout</a></p></div>";
	if($_SESSION['pwdRst']==1) {
		echo "<p>You must change your password now!</p>";
	}
		
}
?>
</div>
<div id="menu">
<?php 
require("menu.php");
?>
</div>
</div>

<?php 

//Check if the page should be redirected to index.php in case the user is not logged in.
function indexRedirect() {

//Must not redirect for these pages.
require "redirect.ini";

$redirect = true;
	
foreach($pages as $page) {
	if (stristr($_SERVER['PHP_SELF'],$page)) {
		$redirect = false;
	}	

}

return $redirect;
}
?>