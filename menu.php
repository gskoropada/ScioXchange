<p><a href="index.php">Home</a> | <a href="questions.php">Questions</a>
<?php
/*
 * Dynamic menu.
 * Provides role dependent menu options.
 */

if(isset($_SESSION['userid'])) {
	$menu = " | <a href='dashboard.php'>Dashboard</a>";
	if ($_SESSION['role']==1 || $_SESSION['role']==2){
		$menu .= " | <a href='admin.php'>Administration</a>";
	}
	echo $menu;
}

?>

</p>
