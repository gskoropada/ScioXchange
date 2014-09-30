<?php
require("connect.php");

switch($_REQUEST['action']) {
	case "rt":
		echo rootTags();
		break;
	case "ct":
		echo listTags($_REQUEST['id']);
		break;
	case "st":
		echo fetchTags($_REQUEST['tags']);
		break;
	case "tree":
		buildTree($_REQUEST['level'], -1,0);
		break;
}


function rootTags() {

global $con;
	
$tags = Array();

$query = "SELECT tag_id, tag, parent_count from tag left join 
	(SELECT count(parent_tag_id) as parent_count, child_tag_id from tag_heirarchy group by child_tag_id) as children
on tag_id = child_tag_id
where parent_count is null";

$result = mysqli_query($con, $query);

$i = 0;

while ($root_tags = mysqli_fetch_array($result)) {
	
	$tags[$i]['tag'] = $root_tags['tag'];
	$tags[$i]['id'] = $root_tags['tag_id'];
	$i++;
		
}

return json_encode($tags);

}

function listTags($tag_id) {
	global $con;
	
	$tags = Array();
	
	$query = "SELECT tag_id, tag from tag inner join tag_heirarchy on tag_id=child_tag_id and parent_tag_id=".$tag_id;

	$result = mysqli_query($con, $query);
	
	$i = 0;
	
	while ($children_tags = mysqli_fetch_array($result)) {
	
		$tags[$i]['tag'] = $children_tags['tag'];
		$tags[$i]['id'] = $children_tags['tag_id'];
		$tags[$i]['parent'] = $tag_id;
		$i++;
	
	}
	
	return json_encode($tags);
	
}

function hasChildren($id) {
	global $con;
	
	$query = "SELECT tag_id, children from tag left join 
	(SELECT parent_tag_id, count(child_tag_id) as children from tag_heirarchy group by parent_tag_id) as parents
on tag_id = parent_tag_id
where tag_id =".$id;
	
	$result = mysqli_query($con, $query);
	
	$children = mysqli_fetch_array($result);
	
	if($children['children'] > 0) {
		return true;
	} else {
		return false;
	}
	
}

function buildTree($depth, $id, $current_level) {
	$tree = Array();
	if($current_level<=$depth) {
		if($id == -1) {
			$tree = json_decode(rootTags());
		} else {
			$tree = json_decode(listTags($id));
		}
		
		foreach($tree as $tag) {
			echo str_repeat("--",$current_level).">".$tag->tag."<br>";
			if(hasChildren($tag->id)) {
				buildTree($depth, $tag->id, $current_level+1);
			}
		}
	}
}

function fetchTags($tag_input) {
	global $con;
	
	$usr_tags = explode(",",$tag_input);
		
	if($tag_input!="") {

		$query = "SELECT tag FROM tag WHERE tag LIKE '".trim($usr_tags[0])."%' ";
		
		for($i=1;$i<sizeof($usr_tags);$i++){
			
			if(!empty(trim($usr_tags[$i]))) {
				$query .= "OR tag LIKE '".trim($usr_tags[$i])."%' ";
			}
		}
				
		$result = mysqli_query($con, $query);
		
		if(!$result) {
			echo "DB Error";
		} else {
			$tagSuggestion = "";
			while($tags = mysqli_fetch_array($result)) {
				$tagSuggestion .= $tags['tag']."  ";
			}
			
			echo $tagSuggestion;
		}
	}
}
?>