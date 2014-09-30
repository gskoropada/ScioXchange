<?php
/*
 * ADMINISTRATIVE TOOLS
 * Provides back-end functions for the user administration panel.
 */


if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']) {
		case "du": //Delete user
			echo "DU action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			deleteUser($con, $_REQUEST['id']);
			break;
		case "au": //Activate user
			echo "AU action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			activateUser($con, $_REQUEST['id']);
			break;
		case "dau": //Deactivate user
			echo "DAU action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			deactivateUser($con, $_REQUEST['id']);
			break;
		case "mu": //Moderate user
			echo "MU action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			moderateUser($con, $_REQUEST['id']);
			break;
		case "rmu": //Remove user moderation
			echo "RMU action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			removeModeration($con, $_REQUEST['id']);
			break;
		case "bd": //Bulk delete action
			echo "BD action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			bulkDelete($con, $_REQUEST['sel']);
			break;
		case "ba": //Bulk activate action
			echo "BA action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			bulkActivate($con, $_REQUEST['sel'], 1);
			break;
		case "bda": //Bulk deactivate action
				echo "BDA action Ok";
				if(!isset($con)) {
					require('connect.php');
				}
				bulkActivate($con, $_REQUEST['sel'], 0);
				break;
		case "bm": //Bulk moderate action
			echo "BM action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			bulkModerate($con, $_REQUEST['sel'], 1);
			break;
		case "bum": //Bulk remove moderation action
			echo "BUM action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			bulkModerate($con, $_REQUEST['sel'], 0);
			break;
		case "cr": //Change role action
			echo "CR action Ok";
			if(!isset($con)) {
				require('connect.php');
			}
			changeRole($con, $_REQUEST['id'], $_REQUEST['role']);
			break;
		default:
			echo "invalid option";
			break;
	}
}

//Verifies if the user has administrative rights.
function chkAdmin($con) {
	$query = "SELECT role FROM user WHERE UserId=".$_SESSION['userid'];
	$admin = false;

	$result = mysqli_query($con,$query);

	if (!$result) {
		echo "DB Error";
	} else {
		$row = mysqli_fetch_array($result);

		if($row['role']==2){
			$admin=true;
		}
	}

	return $admin;
}

//Delete user function
function deleteUser($con, $userid) {
	$query = "DELETE FROM user WHERE UserID=".$userid;
	
	$result = mysqli_query($con,$query);
	
	if (!$result) {
		echo "DB Error";
	} else {
		echo "User delete ok";
	}
}

//Activate user function
function activateUser($con, $userid) {
	$query = "UPDATE user SET active = 1 WHERE UserId=".$userid;
	$result = mysqli_query($con,$query);
	
	if (!$result) {
		echo "DB Error";
	} else {
		$query = "DELETE from validation_key WHERE UserId =".$userid;
		$result = mysqli_query($con, $query);
		if(!$result) {
			echo "DB Error";
		} else {
			echo "User activate ok";
		}
	}
}

//Deactivate user function
function deactivateUser($con, $userid) {
	$query = "UPDATE user SET active = 0 WHERE UserId=".$userid;
	$result = mysqli_query($con,$query);

	if (!$result) {
		echo "DB Error";
	} else {
		echo "User deactivate ok";
	}
}

//Moderate user function
function moderateUser($con, $userid) {
	$query = "UPDATE user SET moderated = 1 WHERE UserId=".$userid;
	$result = mysqli_query($con,$query);

	if (!$result) {
		echo "DB Error";
	} else {
		echo "User moderate ok";
	}
}

//Remove moderation function
function removeModeration($con, $userid) {
	$query = "UPDATE user SET moderated = 0 WHERE UserId=".$userid;
	$result = mysqli_query($con,$query);

	if (!$result) {
		echo "DB Error";
	} else {
		echo "User unmoderate ok";
	}
}

//Bulk delete function
function bulkDelete($con, $sel) {
	$query = "DELETE FROM user WHERE (UserId) IN (";
	
	$i=0;
	
	foreach($sel as $id) {
		$i++;
		$query .= $id;
		if($i == count($sel)) {
			$query .= ")";
		} else {
			$query .= ",";
		}
	}
	
	$result = mysqli_query($con, $query);
	
	if (!$result) {
		echo "DB Error";
		echo mysqli_error($con);
	} else {
		echo "User bulk delete ok";
	}
	
}

//Bulk activate function
function bulkActivate($con, $sel, $status) {
	$query = "UPDATE user SET active=".$status." WHERE UserId IN (";
	
	$i=0;

	foreach($sel as $id) {
		$i++;
		$query .= $id;
		if($i == count($sel)) {
			$query .= ")";
		} else {
			$query .= ",";
		}
	}

	$result = mysqli_query($con, $query);

	if (!$result) {
		echo "DB Error";
		echo mysqli_error($con);
	} else {
		echo "User bulk activate ok";
	}

}

//Bulk moderate function
function bulkModerate($con, $sel, $status) {
	$query = "UPDATE user SET moderated=".$status." WHERE UserId IN (";

	$i=0;

	foreach($sel as $id) {
		$i++;
		$query .= $id;
		if($i == count($sel)) {
			$query .= ")";
		} else {
			$query .= ",";
		}
	}

	$result = mysqli_query($con, $query);

	if (!$result) {
		echo "DB Error";
		echo mysqli_error($con);
	} else {
		echo "User bulk moderate ok";
	}
}

//Change role function
function changeRole($con, $id, $role) {
	$query = "UPDATE user SET role = ".$role." WHERE UserId=".$id;
	$result = mysqli_query($con,$query);
	
	if (!$result) {
		echo "DB Error";
	} else {
		echo "User change role ok";
	}
}
?>