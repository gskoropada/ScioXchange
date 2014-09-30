<?php
/*//object to catch error messages
$message = '';
//database connection
$db = new mysqli('localhost', 'root', '', 'php_assignment');
//assign error messages to the $message object
if ($db->connect_error) {
	$message = $db->connect_error;
} else{ //if nothing is wrong then get the question from the url id, sanitizing the data first
	$sql = 'SELECT user_id, screen_name FROM user';
	$select_user = $db->query($sql);
	if ($db->error){ //if something is wrong with the query then show the error
		$message = $db->error;
	} else {
	}
}*/

$title = "Scio Exchange - Ask a question";
$styles = ["main.css", "header.css"];
$scripts = ["jquery-1.11.1.js","ask.js"];

include "html_start.php";
include "header.php"; ?>
<div id="breadcrumbs">
	<p><a href="index.php">Home</a> > <a href="questions.php">Questions</a> > Ask</p>
</div>
<h2>Ask a Question!</h2>
<form action="question_submit.php" name="ask" method="post">
	<p>
	<label for="user">User: </label><!-- This select button allows quick changes between users to comment with. This is a temporary way for me to test the  site.-->
	<!-- <select name="user" id="user"> -->
		<?php /*while ($user = $select_user->fetch_assoc()) { //display all users currently in the database.
		?>
			<option value="<?php echo $user['user_id'] ?>"><?php echo $user['screen_name'] ?></option>
			<?php } //end while loop*/
			?>
		<!-- </select> -->
	</p>
	<p>
		<label for="title">Question Title:</label>
		<input type="text" name="title" id="title">
	</p>
	<p>
		<label for="question_text">Question:</label>
		<input type="textarea" name="question_text" id="question_text">
	</p>
	<p>
		<label for="tags">Tags:</label>
		<input type="text" name="tags" id="inTags"><span id="tagSuggest"></span>
		</br>Multiple tags can be seperated with a comma and a space, like so: "tag1, tag2"
	</p>
	<p>
		<input type="submit" name="submit" id="ask" value="Ask!">
	</p>
</form>

</body>