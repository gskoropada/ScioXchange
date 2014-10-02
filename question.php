<?php

require ("connect.php");

$sql = 'SELECT author, question_title, content, tags, timestamp, screenName FROM question INNER JOIN user ON author = UserID WHERE question_id = '.$_GET['id'];
$result = mysqli_query($con, $sql);
$question = mysqli_fetch_assoc($result);

$title = "Scio Exchange - Question";
$styles =["main.css", "header.css", "questions.css"];
$scripts = ["jquery-1.11.1.js","reply.js","questions.js"];

include "html_start.php";
include "header.php"; 
require "vote.php";

$d = new DateTime($question['timestamp']);
echo "<div id='wrapper'>";
echo "<div class='question'><p class='qtitle'>".$question['question_title']."</p>"; 
echo "<span class='qexcerpt'><p>".$question['content']."</p></span>";
echo "<span class='qstats'>".date_format($d,"d-M-y");
if(isset($_SESSION['userid'])) {
	echo " | <span id='".$_GET['id']."' class='btnQComment click_option'>Leave a comment</span>";
}
echo "<span class='tags'>";
$tags = explode(',' , $question['tags']);
foreach($tags as $tag) {
	echo "<a href='#'>$tag</a> ";
} 
echo "</span></span><span class='qauthor'><a href=\"user_profile.php?id=".$question['author']."\">" . $question['screenName']."</a></span>";
getComments(0, $_GET['id'] );
echo "</div>";

getAnswers(); 

if(isset($_SESSION['userid'])) {
	require("forms/reply_form.php");
}
echo "</div>"; //Wrapper
require("html_end.php"); 

function getAnswers() {
	global $con;
	
	$sql = 'SELECT answer_id, author, content, (positive_votes-negative_votes) as rating, screenName, timestamp FROM answer INNER JOIN user on author = UserID WHERE question ='.$_GET['id']." ORDER BY rating DESC";
	$result = mysqli_query($con, $sql);
	
	while($answers = mysqli_fetch_assoc($result)) {
		echo "<div class='answer' id='ans_".$answers['answer_id']."'><span class='ans_content'>".nl2br($answers['content'])."</span><span class='aauthor'><a href=\"user_profile.php?id="
		.$answers['author']."\">".$answers['screenName']."</a></span>";
		echo "<span id='rating_".$answers['answer_id']."' class='rating ";
		if($answers['rating']>0) {
			echo "r_pos";
		} else if ($answers['rating']<0){
			echo "r_neg";
		}
		echo "'>".$answers['rating']."</span>";
		echo "<div class='astats'>".date_format(new DateTime($answers['timestamp']), "d-M-y");
		if(isset($_SESSION['userid'])) {
			echo " | <span id='".$answers['answer_id']."' class='click_option btnAComment'>Leave a comment</span>";
			if(checkVote($answers['answer_id'])) {
				echo " | <span id='".$answers['answer_id']."' class='click_option thumb_up'>Thumbs Up</span>";
				echo " | <span id='".$answers['answer_id']."' class='click_option thumb_down'>Thumbs down</span>";
			} else {
				echo " | Already voted.";
			}
		}
		echo "</div>";
		getComments(1, $answers['answer_id']);
		echo "</div>";		
	}
	
}

function getComments($type, $id) {
	global $con;
	
	$sql = "SELECT comment_id, screenName, comment, timestamp, comment_author FROM comment INNER JOIN user ON UserID = comment_author WHERE link = $id AND link_to = $type";
	$result = mysqli_query($con, $sql);
	
	while($comment = mysqli_fetch_assoc($result)) {
		echo "<div class='comment'>";
		echo "<span class='cauthor'><a href='user_profile.php?id=".$comment['comment_author']."'>".$comment['screenName']."</a></span>";
		echo "<pre>".$comment['comment']."</pre>";
		echo "<span class='comment_timestamp'>".date_format(new DateTime($comment['timestamp']), "d-M-y")."</span>";
		echo "</div>";
	}
}

?>