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
	case "cloud":
		echo tagsCloud();
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

function getTagCount($tag) {
	global $con;
	
	$sql = "SELECT tag_count FROM tag WHERE tag = '$tag' ";
	
	$result = mysqli_query($con,$sql);
	
	if (!$result) {
		return false;
	} else {
		$count = mysqli_fetch_array($result);
		return $count[0]; 
	}
}

function tagsCloud() {
	/*word_list = [
	{text: "Lorem", weight: 15},
	{text: "Ipsum", weight: 9, link: "http://jquery.com/"},
	{text: "Dolor", weight: 6},
	{text: "Sit", weight: 7},
	{text: "Amet", weight: 5}*/
	global $con;
	$query = "SELECT tag, tag_count FROM tag ORDER BY tag_count DESC LIMIT 35";
	$result = mysqli_query($con, $query);
	
	if(!$result) {
		echo mysqli_error($con);
	} else {
		$cloud = "[";
		$rows = mysqli_num_rows($result);
		$i = 0;
		while($tag = mysqli_fetch_assoc($result)) {
			$cloud .= "{\"text\": \"".ucwords($tag['tag'])."\", \"weight\":".$tag['tag_count'].", \"link\": \"tag.php?tag=".trim($tag['tag'])."\"}";
			$i++;
			if($i != $rows) {
				$cloud .= ",";
			}
		}
		$cloud .= "]";
		echo $cloud;
	}
}
?>