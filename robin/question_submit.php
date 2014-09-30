<?php
if(!isset($_POST['submit']))  {//security validation
	echo 'unauthorised access';	
	}
	else {
	// data entered in all boxes
	$required = array('user', 'title', 'question_text', 'tags');
	$error = false;
	foreach($required as $field) {
		if (empty($_POST[$field])) {
		$error = true;
	}
	}
	if ($error) {
		echo "All fields are required.";
	} else {
$author = $_POST['user'];
$question_title = $_POST['title'];
$content = $_POST['question_text'];
$tags = $_POST['tags'];
//object to catch error messages
$message = '';
//database connection
$db = new mysqli('localhost', 'root', '', 'php_assignment');
//assign error messages to the $message object
if ($db->connect_error) {
	$message = $db->connect_error;
} else{ //if nothing is wrong then get the question from the url id, sanitizing the data first
	$sql = "INSERT INTO question (author, question_title, content, tags) values ('$author', '$question_title', '$content', '$tags')";
mysqli_query($db, $sql)
	or die('Cannot update database.');
?>
!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PHP assignment</title>
</head>
<body>
<div>
<?php include"includes/header.php" ?>
<div id="breadcrumbs">
	<p><a href="index.php">Home</a> > <a href="questions.php">Questions</a> > <?php echo $question_title ?> submitted!</p>
</div>
<h2>Question Submitted!</h2>
<p><a href="questions.php">Return</a></p>
</div>
<?php
}
}
}
?>