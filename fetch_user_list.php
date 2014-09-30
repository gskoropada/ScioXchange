<?php
/*
 * FECTH USER LIST functions
 * Provides back-end functionality to the user administration page.
 */

require('admin_tools.php');
require("session.php");


if(isset($_REQUEST['offset']) && isset($_REQUEST['records'])) {

	//Check if a session is started and if the user has the right privileges to request 
	// the information
	if(isset($_SESSION['role'])) {

		require("connect.php");
		if ($_SESSION['role']==2 && chkAdmin($con)) {
			
			$filter = "";
			$order = "";
			
			if(isset($_REQUEST['filter'])) { //Sets filter statement
				$filter = getFilter($_REQUEST['filter'], $_REQUEST['criteria']);
			}
			
			if(isset($_REQUEST['order'])) { //Sets sorting condition
				$order = getOrder($_REQUEST['order'], $_REQUEST['dir']);
			}
			
			echo listUsers($con,$_REQUEST['offset'],$_REQUEST['records'], $filter, $order);
	
		} else {
			echo "Not enough privileges";
		}
	} else {
		echo "role not ok";
	}
	
} elseif(isset($_REQUEST['function'])) { //Checks for additional function options
	if(isset($_SESSION['role'])) {
	
		require("connect.php");
		if ($_SESSION['role']==2 && chkAdmin($con)) {
	
			switch($_REQUEST['function']) {
				case "count_users": //Return total user count
					echo countUsers($con);
					break;
				default:
					echo false;
					break;
			}
	
		} else {
			echo "Not enough privileges";
		}
	} else {
		echo "role not ok";
	}
}

//Count user function
function countUsers($con) {
	$query = "SELECT count(*) as totalusers FROM user";
	
	$result = mysqli_query($con,$query);
	
	if(!$result){
		echo "Error";
	} else {
	
		$count = mysqli_fetch_array($result);
		
		return $count['totalusers'];
	
	}
}

//List users function. Returns a JSON encoded string to the client.
function listUsers($con, $offset, $records, $filt, $order) {
	
	$query = "SELECT UserID, email, screenName, reputation, avatar, active, moderated, role FROM user ";
	$query .= $filt . $order . " LIMIT ".$offset.",".$records;

	$result = mysqli_query($con,$query);

	if(!$result){
		echo "Error";
	} else {
		
		$i=0;
		
		while($row = mysqli_fetch_array($result)) {
			$data[$i] = $row;
			$i++;	
		}
		
		return json_encode($data);
		
	}
}

//Check role field value and returns a verbose description
function getRole($role) {

	switch($role){
		case 0:
			$roledescription = "User";
			break;
		case 1:
			$roledescription = "Moderator";
			break;
		case 2:
			$roledescription = "Administrator";
			break;
		default:
			$roledescription = $role;
			break;
	}

	return $roledescription;
}

//Returns a SQL filter statement for the selected field and criteria
//Needs further refinement
function getFilter($col, $criteria) {
	$filter = "";
	
	switch($col) {
		case "ID":
			$filter = "WHERE UserID=".$criteria;
			break;
		case "EML":
			$filter = "WHERE email='".$criteria."'";
			break;
		case "SCN":
			$filter = "WHERE screenName='".$criteria."'";
			break;
		case "ROLE":
			$filter = "WHERE role=".$criteria;
			break;
		case "ACT":
			$filter = "WHERE activated=".$criteria;
			break;
		case "MOD":
			$filter = "WHERE moderated=".$criteria;
			break;
		default:
			$filter = "";
	}
	
	return $filter;
}

//Returns an ORDER BY SQL statement according to the field and sort direction.
function getOrder($col, $dir) {
	$order = "";
	
	switch($col) {
		case "ID":
			if($dir!=""){
				$order = " ORDER BY UserID ".$dir;
			}
			break;
		case "EML":
			if($dir!=""){
				$order = " ORDER BY email ".$dir;
			}
			break;
		case "SCN":
			if($dir!=""){
				$order = " ORDER BY ScreenName ".$dir;
			}
			break;
		case "ROLE":
			if($dir!=""){
				$order = " ORDER BY role ".$dir;
			}
			break;
		case "ACT":
			if($dir!=""){
				$order = " ORDER BY active ".$dir;
			}
			break;
		case "MOD":
			if($dir!=""){
				$order = " ORDER BY moderated ".$dir;
			}
			break;
		default:
			$order = "";
	}
	
	return $order;
}

?>