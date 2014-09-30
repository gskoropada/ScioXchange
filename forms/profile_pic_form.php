
<form id='profile_pic' method='post' action='' enctype='multipart/form-data'>
<?php
echo "<img src='";
if(!file_exists("profile_pics/".$_SESSION['avatar'].".jpg")) {
	echo "profile_pics/default.png'";
} else {
	echo "profile_pics/".$_SESSION['avatar'].".jpg'";
} 
echo "class='profile_pic' id='avatar'>";
?>
	<input type='file' name='pic' id='inPic'><br>
	<input type='hidden' name='action' value=''>
	<div id="btnPicSave"></div>
</form>