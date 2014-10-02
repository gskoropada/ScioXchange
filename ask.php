<?php

$title = "Scio Exchange - Ask a question";
$styles = ["main.css", "header.css"];
$scripts = ["jquery-1.11.1.js","ask.js"];

include "html_start.php";
include "header.php"; 

require("forms\ask_form.php");

require("html_end.php");
?>