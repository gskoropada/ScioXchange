<?php
$title = "Scioxchange - Questions by tag";
$styles = ["main.css", "header.css", "questions.css"];
$scripts = ["question_functions.js", "tags.js"];

require "html_start.php";
require "header.php";

$tag = $_GET['tag'];

require "forms/tag_list.php";
require "html_end.php";
?>