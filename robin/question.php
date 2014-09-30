<?php
//object to catch error messages
$message = '';
//database connection
$db = new mysqli('localhost', 'root', '', 'php_assignment');
//assign error messages to the $message object
if ($db->connect_error) {
	$message = $db->connect_error;
} else{ //if nothing is wrong then get the question from the url id, sanitizing the data first
	$sql = 	'SELECT question.*, user.user_id, user.screen_name, comment.*, user_2.user_id AS user_id2, user_2.screen_name AS screen_name2 
				FROM question LEFT JOIN user ON author=user_id LEFT JOIN comment ON question_id=link LEFT JOIN user AS user_2 
				ON comment_author=user_2.user_id WHERE question_id=' . $db->real_escape_string($_GET['id']) . ' GROUP BY comment_id ORDER BY comment_id DESC';
	$sql2 = 'SELECT question.*, user.user_id, user.screen_name, comment.*, user_2.user_id AS user_id2, user_2.screen_name AS screen_name2 
				FROM question LEFT JOIN user ON author=user_id LEFT JOIN comment ON question_id=link LEFT JOIN user AS user_2 
				ON comment_author=user_2.user_id WHERE question_id=' . $db->real_escape_string($_GET['id']) . ' GROUP BY comment_id ORDER BY comment_id DESC';
	$sql3 = 'SELECT user_id, screen_name FROM user';
	$result = $db->query($sql);
	$result2 = $db->query($sql2);
	$select_user = $db->query($sql3);
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
<?php include"includes/header.php"; ?>
<div id="breadcrumbs">
	<p><a href="index.php">Home</a> > <a href="questions.php">Questions</a> > <?php echo $row['question_title'] ?></p>
</div>
<?php if ($message) { //if there is an error message stored in $message then show it.
	} else { ?>
<h2><?php echo $row['question_title']; ?></h2>
<div><!-- This is the original question-->
		<p><?php echo $row['content']; ?></p>
		<p>				<?php //display all the tags as individual elements.
				$tags = explode(', ' , $row['tags']);
				foreach($tags as $tag) {
					echo "<a href='#'>$tag</a> ";
				} ?> </br><a href="user_profile.php?id=<?php echo $row['user_id']; ?>"><?php echo $row['screen_name']; ?></a></p>
</div>
<div><!-- These are the replies-->
		<?php while ($row2 = $result2->fetch_assoc()) { ?>
		<ul>
			<li><?php echo $row2['comment']; ?>
			</br><a href="user_profile.php?id=<?php echo $row2['comment_author']; ?>"><?php echo $row2['screen_name2']; ?></a>
			</li>
		</ul>
	<?php }
	?>
</div>
<div><!-- Here's where you can add a reply-->
<form action="reply.php" name="reply" method="post">
	<p>
	<label for="user">User: </label><!-- This select button allows quick changes between users to comment with. This is a temporary way for me to test the  site.-->
	<select name="user" id="user">
		<?php while ($user = $select_user->fetch_assoc()) { //display all users currently in the database.
		?>
			<option value="<?php echo $user['user_id'] ?>"><?php echo $user['screen_name'] ?></option>
			<?php } //end while loop
			?>
		</select>
	</p>
	<p>
		<label for="answer">Answer:</label>
		<input type="text" name="answer" id="answer">
	</p>
	<p>
		<input type="submit" name="reply" id="reply" value="Reply">
		<input type="hidden" name="question" id="question" value="<?php echo $db->real_escape_string($_GET['id']) ?>">
	</p>
</form>
</div>
<?php } ?>
</div>
</body>
</html>