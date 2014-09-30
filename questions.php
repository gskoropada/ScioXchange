<?php
/*$hostname = 'localhost';
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
}*/

$message = '';

require "connect.php";

$title = "Scio Exchange - Questions";
$styles = ["main.css","header.css"];
$scripts = ["jquery-1.11.1.js"];

require "html_start.php";
require "header.php";

$sql = 'SELECT question_id, question_title, tags, screenName, UserID, author, COUNT(comment_id) AS no_replies FROM (question LEFT JOIN user ON UserID=author) LEFT JOIN comment ON link=question_id GROUP BY question_id ORDER BY question_id DESC';

?>
<div id="breadcrumbs">
	<p><a href="index.php">Home</a> > Questions</p>
</div>
<?php if ($message) { //if there is an error message stored in $message then show it.
		echo "<h2>$message</h2>";
	} else {
	?>
	<h2>Questions!</h2>
<p><a href ="ask.php">Ask your own</a></p>
		<?php 
		$result = mysqli_query($con, $sql);
		if($result) {
			while ($row = mysqli_fetch_array($result)) //loop through a list of all the questions.
			{ ?>
		<ul>
			<li><a href="question.php?id=<?php echo $row['question_id']; ?>"><?php echo $row['question_title']; ?></a>
			</br>Author: <a href="user_profile.php?id=<?php echo $row['UserID']; ?>"><?php echo $row['screenName']; ?></a>
			</br>Replies: <?php echo $row['no_replies']; ?>
			</br>Tags: 
				<?php //display all the tags as individual elements.
				$tags = explode(', ' , $row['tags']);
				foreach($tags as $tag) {
					echo "<a href='#'>$tag</a> ";
				}?></li>
		</ul>
	<?php }
	} //end of loop
}

require "html_end.php";
?>
