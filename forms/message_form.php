<div id="breadcrumbs">
	<p><a href="index.php">Home</a> > Compose Message</p>
</div>
<form action="message_submit.php" name="message" method="post">
	<p>
		<label for="subject">Subject:</label>
		<input type="text" name="subject" id="inSubject">
	</p>
	<p>
		<label for="message_text">Message:</label>
		<textarea name="message" id="inMessage"></textarea>
	</p>
	<p>
		<button name="submit" id="btnSend">Send!</button><span id="msgArea"></span>
	</p>
		<input type="hidden" name="send_to" id="send_to_id" value="<?php echo $_GET['id']; ?>">
	</form>