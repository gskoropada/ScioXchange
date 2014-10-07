<?php
//object to catch error messages
$message = '';
require "connect.php";

	$sql = 'SELECT user.*, COUNT(question_id) AS question_count FROM user INNER JOIN question ON question.author=user_id INNER JOIN answer on answer.author=user_id WHERE user_id=' . $db->real_escape_string($_GET['id']) . ' GROUP BY user_id';
	$sql2 = 'SELECT user_id, COUNT(question_id) AS no_question, author FROM user LEFT JOIN question ON user_id=author WHERE user_id='. $db->real_escape_string($_GET['id']);
	$sql3 = 'SELECT user_id, COUNT(answer_id) AS no_answer, author FROM user LEFT JOIN answer ON user_id=author WHERE user_id='. $db->real_escape_string($_GET['id']);
	$result = $db->query($sql);
	$result2 = $db->query($sql2);
	$result3 = $db->query($sql3);
	if ($db->error){ //if something is wrong with the query then show the error
		$message = $db->error;
	} else {
		$row = $result->fetch_assoc();
		$count1 = $result2->fetch_assoc();
		$count2 = $result3->fetch_assoc();		
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
	<p><a href="index.php">Home</a> > Users > <?php echo $row['screen_name']; ?></p>
</div>
<?php if ($message) { //if there is an error message stored in $message then show it.
	} else { ?>
<h2><?php echo $row['screen_name']; ?></h2>
<p><?php echo  $row['email']; ?></p>
<p>Reputation: <?php echo $row['reputation']; ?></p>
<p>Questions: <a href="user_questions.php?id=<?php echo $row['user_id'] ?>"><?php echo $count1['no_question']; ?></a></p>
<p>Comments: <?php echo $count2['no_comment']; ?></p>

<?php
}
?>
</div>
</body>
</html>