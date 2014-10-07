<?php

if(!isset($con)) {
	require "connect.php";
}

if(isset($_POST['slider'])) {
	if($_POST['slider'] == 1) {
		fetchQuestions(5,0,0);
	}
}

if(isset($_POST['fetch'])) {
	fetchQuestions($_POST['fetch'], $_POST['id'], $_POST['offset']);
}


function listQuestions($slider) {
	global $con;

	$query =
	"SELECT question.author as auth, question_id, question_title, question.content as q, tags, replies, screenName, timestamp FROM question
	LEFT JOIN
		(SELECT count(answer_id) as replies, question from answer group by question) as reply
	on question_id = question
	INNER JOIN user on question.author = UserID
	ORDER BY timestamp DESC ";

	if($slider) {
		$query .= "LIMIT 5";
	}
	
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
			echo "<span class='qexcerpt'><pre>$q</pre></span>";
			echo "<span class='qstats'>".date_format($d, "d-M-Y")." | $r";
			echo "<span class='tags'>";
			$tags = explode(', ' , $question['tags']);
				
			foreach($tags as $tag) {
				echo "<a href='#'>$tag</a> ";
			}
				
			echo "</span></span>";
			if($slider) {
				getAnswers($question['question_id'], true);
			}
			echo "</div>\n";
		}
	}
}

function getAnswers($id, $slider) {
	global $con;

	$sql = "SELECT answer_id, author, content, (positive_votes-negative_votes) as rating, screenName, timestamp FROM answer INNER JOIN user on author = UserID WHERE question = $id ORDER BY rating DESC";
	if($slider) {
		$sql .= " LIMIT 2";
	}
	
	$result = mysqli_query($con, $sql);

	while($answers = mysqli_fetch_assoc($result)) {
		echo "<div class='answer' id='ans_".$answers['answer_id']."'><span class='ans_content'><pre>".$answers['content']."</pre></span><span class='aauthor'><a href=\"user_profile.php?id="
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
				if($_SESSION['userid'] != $answers['author']) {
					echo " | <span id='".$answers['answer_id']."' class='click_option thumb_up'>Thumbs Up</span>";
					echo " | <span id='".$answers['answer_id']."' class='click_option thumb_down'>Thumbs down</span>";
				}
			} else {
				echo " | Already voted.";
			}
		}
		echo "</div>";
		if(!$slider) {
			getComments(1, $answers['answer_id']);
		}
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

function hasAnswered($id) {
	global $con;
	
	$query = "SELECT count(answer_id) FROM answer WHERE question=$id AND author=".$_SESSION['userid'];
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo myseli_error($con);
	} else {
		$h = mysqli_fetch_array($result);
		if($h[0] > 0) {
			return true;
			
		} else {
			return false;
		}
	}
}

function fetchQuestions($num, $id, $offset) {
	global $con;
	if($id != 0) {
		$by = "WHERE question.author = $id ";
	} else {
		$by ="";
	}
	
	if($num != 0) {
		$limit = "LIMIT $num";
	} else {
		$limit = "";
	}
	
	if($offset != 0) {
		$off = "OFFSET $offset";
	} else {
		$off = "";
	}
	
	$query =
	"SELECT question.author as auth, question_id, question_title, question.content as q, tags, replies, screenName, timestamp FROM question
	LEFT JOIN
		(SELECT count(answer_id) as replies, question from answer group by question) as reply
	on question_id = question 
	INNER JOIN user on question.author = UserID
	$by 
	ORDER BY timestamp DESC $limit $off";
	
	$result = mysqli_query($con, $query);
	$questions = array();
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		while ($q = mysqli_fetch_assoc($result)) {
			$questions[] = $q;
		}
		echo json_encode($questions);
	}
}

?>