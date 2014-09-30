<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'php_assignment';
//object to catch error messages
$message = '';
//database connection
$db = new mysqli($hostname, $username, $password, $dbname);
//assign error messages to the $message object
if ($db->connect_error) {
	$message = $db->connect_error;
	} else{	
//if nothing is wrong then send the query
	$sql = 'SELECT question_id, question_title, tags, screen_name, user_id, author, COUNT(comment_id) AS no_replies FROM (question LEFT JOIN user ON user_id=author) LEFT JOIN comment ON link=question_id GROUP BY question_id ORDER BY question_id DESC';
	$result = $db->query($sql);
	if ($db->error){ //if something is wrong with the query then show the error
		$message = $db->error;
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
	<p><a href="index.php">Home</a> > Questions</p>
</div>
<?php if ($message) { //if there is an error message stored in $message then show it.
		echo "<h2>$message</h2>";
	} else {
	?>
	<h2>Questions!</h2>
<p><a href ="ask.php">Ask your own</a></p>
		<?php while ($row = $result->fetch_assoc()) //loop through a list of all the questions.
		{ ?>
		<ul>
			<li><a href="question.php?id=<?php echo $row['question_id']; ?>"><?php echo $row['question_title']; ?></a>
			</br>Author: <a href="user_profile.php?id=<?php echo $row['user_id']; ?>"><?php echo $row['screen_name']; ?></a>
			</br>Replies: <?php echo $row['no_replies']; ?>
			</br>Tags: 
				<?php //display all the tags as individual elements.
				$tags = explode(', ' , $row['tags']);
				foreach($tags as $tag) {
					echo "<a href='#'>$tag</a> ";
				}?></li>
		</ul>
	<?php } //end of loop
}?>
</div>
</body>
</html>