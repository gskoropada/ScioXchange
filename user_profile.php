<?php
//Variables for html_start.php
$title = "Scio Exchange - User Profile";
$styles = ["main.css","header.css","dashboard.css"];
$scripts = ["question_functions.js", "dashboard.js"];

require "html_start.php";
require "header.php";
require "user_profile_functions.php";
?>
<h1><?php screenNameFromUserID($_GET['id']); ?></h1>
<?php
if($_SESSION['userid'] == $_GET['id']) {
	echo "<p>This is how other people see your profile</p>";
} else {
	echo "<p><a href = message.php?id=" . $_GET['id'] .">send message</a></p>";
}
?>
<div id="usrStats">
	<p><?php screenNameFromUserID($_GET['id']); ?> has asked <?php getQuestionCount($_GET['id']); ?> questions.</p>
	<p><?php screenNameFromUserID($_GET['id']); ?> has received <?php getAnswersCount($_GET['id']); ?>  answers.</p>
</div>
<?php
require "html_end.php";
?>

<a href=\"user_profile.php?id=".$question['author']."\">