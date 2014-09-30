<?php
if(!isset($_POST['submit']))  {//security validation
	echo 'come in through the front door';	
	}
else {
		// data entered in all boxes
	$required = array('user', 'answer', 'question');
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
$comment = $_POST['answer'];
$question = $_POST['question'];
//object to catch error messages
$message = '';
//database connection
$db = new mysqli('localhost', 'root', '', 'php_assignment');
//assign error messages to the $message object
if ($db->connect_error) {
	$message = $db->connect_error;
} else{ //if nothing is wrong then get the question from the url id, sanitizing the data first
	$sql = "INSERT INTO comment (comment_author, comment, link) values ('$author', '$comment', '$question')";
mysqli_query($db, $sql)
	or die('Cannot update database: ' . mysqli_error($db));
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
	<p><a href="index.php">Home</a> > <a href="questions.php">Questions</a> > Submitted!</p>
</div>
<h2>reply Submitted!</h2>
<p><a href="question.php?id=<?php echo $question ?>">Return</a></p>
</div>
<?php
}
}
}
?>