<p><a href="index.php">Home</a> | <a href="questions.php">Questions</a>
<?php
/*
 * Dynamic menu.
 * Provides role dependent menu options.
 */

if(isset($_SESSION['userid'])) {
	if($_SESSION['active']==1) {
		$menu = " | <strong><a href='ask.php'>Ask a question!</a></strong>";
		$menu .= " | <a href='dashboard.php'>Dashboard</a>";
		if ($_SESSION['role']==1 || $_SESSION['role']==2){
			$menu .= " | <a href='admin.php'>Administration</a>";
		}
		echo $menu;
	}
}

?>

</p>
