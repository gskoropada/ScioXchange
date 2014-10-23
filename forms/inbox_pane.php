<div id="wrapper">
<div id="inbox_menu">
<p>
	<a href="inbox.php">Inbox<span id="unread_msg_count"></span></a> | 
	<a href="inbox.php?sent=1" >Sent messages</a>
</p>
</div>
<div id="inbox_list">
	<div id="msg_list_header">
		<span id="total_msg_count"></span> 
		<?php 
		if(isset($_GET['sent'])) {
			echo " message(s) sent.";
		} else {
			echo " message(s) received.";
		}
		?>
	</div>
	<div id="msg_list"></div>
</div>
<div id="inbox_preview">
	<h2>Click on a message to expand</h2>
</div>
</div>