<?php
//Variables for html_start.php
$title = "Scio Exchange - User Profile";
$styles = ["main.css","header.css","account.css"];
$scripts = ["question_functions.js"];

require "html_start.php";
require "header.php";
require "user_profile_functions.php";
?>
<div class="profile_pic">
	<img src="profile_pics/<?php avatarFromUserID($_GET['id']);?>" />
</div>
<h1><?php screenNameFromUserID($_GET['id']); ?></h1>

<?php
if($_SESSION['userid'] == $_GET['id']) {
	echo "<p>This is how other people see your profile</p>";
} else {
	echo "<p><img src='images/mail_icon.png' style='width:18px; heigth:18px;'/><a href = message.php?id=" . $_GET['id'] .">Send message</a></p></div>";
}
?>

<div id="usrStats">
	<p><?php screenNameFromUserID($_GET['id']); ?> has asked <?php getQuestionCount($_GET['id']); ?> questions.</p>
	<p><?php screenNameFromUserID($_GET['id']); ?> has received <?php getAnswersCount($_GET['id']); ?>  answers.</p>
</div>


<?php
require "html_end.php";
?>