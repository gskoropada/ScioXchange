<?php

$title = "Scio Exchange - Ask a question";
$styles = ["main.css", "header.css","ask.css"];
$scripts = ["ask.js"];

include "html_start.php";
include "header.php"; 

require("forms/ask_form.php");

require("html_end.php");
?>