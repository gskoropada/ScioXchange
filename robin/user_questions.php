<!-- This page displays a list of questions authored by one specific user.-->
<?php
//object to catch error messages
$message = '';
//database connection
$db = new mysqli('localhost', 'root', '', 'php_assignment');
//assign error messages to the $message object
if ($db->connect_error) {
	$message = $db->connect_error;
} else{ //if nothing is wrong then send the query
	$sql = 'SELECT screen_name, user_id FROM user WHERE user_id=' . $db->real_escape_string($_GET['id']);
	$sql2 = 'SELECT question_id, question_title, tags, screen_name, user_id, author, COUNT(comment_id) AS no_replies FROM (question LEFT JOIN user ON user_id=author) LEFT JOIN comment ON link=question_id WHERE user_id=' . $db->real_escape_string($_GET['id']) . ' GROUP BY question_id';
	$result = $db->query($sql);
	$result2 = $db->query($sql2);
	if ($db->error){ //if something is wrong with the query then show the error
		$message = $db->error;
	} else {
		$row = $result->fetch_assoc();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PHP assignment</title>
</head>
<body>
<div>
<?php include"includes/header.php" ?>
<div id="breadcrumbs">
	<p><a href="index.php">Home</a> > Users	> <a href="user_profile.php?id=<?php echo $row['user_id'] ?>"><?php echo $row['screen_name'] ?></a> > Questions
</div>
<?php if ($message) { //if there is an error message stored in $message then show it.
		echo "<h2>$message</h2>";
	} else {
	?>
	<h2><?php echo $row['screen_name'] ?></h2>
		<?php while ($row = $result2->fetch_assoc()) //loop through a list of all the questions.
		{ ?>
		<ul>
			<li>Title: <a href="question.php?id=<?php echo $row['question_id']; ?>"><?php echo $row['question_title']; ?></a>
			</br>Replies: <?php echo $row['no_replies']; ?>
			</br>Tags: <?php echo $row['tags']; ?></li>
		</ul>
	<?php } //end of loop
}?>
</div>
</body>
</html>