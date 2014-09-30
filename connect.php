<?php
/*
 * DB CONNECTION code snippet to be included accross the site where needed.
 */

require 'conn_details.ini';

$con = new mysqli($dbserver, $dbusr, $dbpwd, $db ) 
	or die ('Could not connect to the database server' . mysql_error());

?>