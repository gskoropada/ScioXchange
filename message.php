<?php
//Variables for html_start.php
$title = "Scio Exchange - Send Message";
$styles = ["main.css","header.css","dashboard.css"];
$scripts = ["question_functions.js", "dashboard.js"];

require "html_start.php";
require "header.php";

require("forms\message_form.php");
//require("forms\message_form_to.php");

?>