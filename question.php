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
require "questions_backend.php";

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

getAnswers($_GET['id'], false); 

if(isset($_SESSION['userid'])) {
	if(getUser($_GET['id'], NOT_ORI_QUESTION)!=$_SESSION['userid']) {
		require("forms/reply_form.php");
	}
}
echo "</div>"; //Wrapper
require("html_end.php"); 
?>