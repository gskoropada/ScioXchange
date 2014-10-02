<?php

require "connect.php";

$title = "Scio Exchange - Questions";
$styles = ["main.css","header.css","questions.css"];
$scripts = ["jquery-1.11.1.js", "questions.js"];

require "html_start.php";
require "header.php";

//echo "<div id='breadcrumbs'>
//	<p><a href='index.php'>Home</a> > Questions</p>
//</div>";
?>
<div id="wrapper">
<h2>Questions!</h2>
<span id="btnAsk" class="click_option">Ask your own</span>

<?php 
listQuestions();

echo "</div>";
require "html_end.php";

function listQuestions() {
	global $con;
	
	$query = 
	"SELECT question.author as auth, question_id, question_title, question.content as q, tags, replies, screenName, timestamp FROM question 
	LEFT JOIN 
		(SELECT count(answer_id) as replies, question from answer group by question) as reply
	on question_id = question
	INNER JOIN user on question.author = UserID
	ORDER BY timestamp DESC";
	
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo "DB Error";
	} else {
		while($question = mysqli_fetch_array($result)) {
			if(empty($question['replies'])) {
				$r = "no replies";
			} else {
				if($question['replies'] == 1) {
					$r = "1 reply";
				} else {
					$r = $question['replies']." replies";
				}
			}
			if(strlen($question['q']) > 255) {
				$q = substr($question['q'],0,255)."...";
			} else {
				$q = $question['q'];
			}
			$d = new DateTime($question['timestamp']);
			echo "<div id=\"".$question['question_id']."\" class='question qredirect click_option'><p class='qtitle'>".$question['question_title']."</p>";
			echo "<span class='qauthor'><a href=\"user_profile.php?id=".$question['auth']."\">".$question['screenName']."</a></span>";
			echo "<span class='qexcerpt'>$q</span>";
			echo "<span class='qstats'>".date_format($d, "d-M-Y")." | $r";
			echo "<span class='tags'>";
			$tags = explode(', ' , $question['tags']);
			
			foreach($tags as $tag) {
				echo "<a href='#'>$tag</a> ";
			}
			
			echo "</span></span></div>\n";
		}
	}
}
?>
