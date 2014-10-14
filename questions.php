<?php

require "connect.php";

$title = "Scio Exchange - Questions";
$styles = ["main.css","header.css","questions.css"];
$scripts = ["questions.js"];

require "html_start.php";
require "header.php";
require "questions_backend.php";

//echo "<div id='breadcrumbs'>
//	<p><a href='index.php'>Home</a> > Questions</p>
//</div>";
echo "<div id='wrapper'><h2>Questions!</h2>";
/*if(isset($_SESSION['userid'])) {
	echo "<span id='btnAsk' class='click_option'>Ask your own</span>";
}*/
listQuestions(false);

echo "</div>";
require "html_end.php";
?>
