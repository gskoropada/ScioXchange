<?php
/*
 * INDEX
 * Default page.
 * Will include more information in the future.
 */

//Variables for html_start.php
$title = "Scio Exchange";
$styles = ["main.css","header.css", "jqcloud.css", "question_slider.css"];
$scripts = ["jqcloud-1.0.4.js","wordcloud.js","question_functions.js", "question_slider.js"]; // Using jqcloud by Luca Ongaro

require("html_start.php");
require("header.php");
require("home_page.php");
require("html_end.php");
?>