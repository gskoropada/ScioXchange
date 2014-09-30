<?php

$message = "";

require ("connect.php");

$sql = 'SELECT question.*, user.UserID, user.ScreenName, comment.*, user_2.UserID AS user_id2, user_2.ScreenName AS screen_name2 FROM question LEFT JOIN user ON author=UserID LEFT JOIN comment ON question_id=link LEFT JOIN user AS user_2 ON comment_author=user_2.UserID WHERE question_id=' . $_GET['id'] . ' GROUP BY comment_id ORDER BY comment_id DESC';
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$sql2 = 'SELECT question.*, user.UserID, user.ScreenName, comment.*, user_2.UserID AS user_id2, user_2.ScreenName AS screen_name2 FROM question LEFT JOIN user ON author=UserID LEFT JOIN comment ON question_id=link LEFT JOIN user AS user_2 ON comment_author=user_2.UserID WHERE question_id=' . $_GET['id'] . ' GROUP BY comment_id ORDER BY comment_id DESC';
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_assoc($result2);

$title = "Scio Exchange - Question";
$styles =["main.css", "header.css"];

include "html_start.php";
include "header.php"; 
?>

<div id="breadcrumbs">
	<p><a href="index.php">Home</a> > <a href="questions.php">Questions</a> > <?php echo $row['question_title'] ?></p>
</div>

<?php 
if ($message) { //if there is an error message stored in $message then show it.
	} else { 
?>
<h2>

<?php 
echo $row['question_title']; 
?>

</h2>
<div><!-- This is the original question-->
<p>

<?php echo $row['content']; ?>

</p>
<p>	
			
<?php //display all the tags as individual elements.
				$tags = explode(',' , $row['tags']);
				foreach($tags as $tag) {
					echo "<a href='#'>$tag</a> ";
				} 
?> 

</br><a href="user_profile.php?id=<?php echo $row['UserID']."\">" . $row['ScreenName']; ?></a></p>
</div>
<div><!-- These are the replies-->

<?php while ($row2 = mysqli_fetch_assoc($result2)) { ?>
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
		<label for="answer">Answer:</label>
		<input type="text" name="answer" id="answer">
	</p>
	<p>
		<input type="submit" name="reply" id="reply" value="Reply">
		<input type="hidden" name="question" id="question" value="<?php echo $_GET['id'] ?>">
	</p>
</form>
</div>
<?php }
require("html_end.php"); ?>
</div>
</body>
</html>