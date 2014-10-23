<?php
require "msg_functions.php";
$id = 4;
$content = "Check your email for the activation email and start enjoying the site! \n The Scio Exchange Team";
$content = addslashes($content);

$content = escape_characters($content);

var_dump ($content);
$msgJSON = "{\"to\":".$id.",\"from\":58,\"subject\":\"Welcome to Scio Exchange!\",\"content\":\"$content\" }";

echo $msgJSON;

$msg = json_decode($msgJSON);

sendMessage($msgJSON);

var_dump($msg);
?>