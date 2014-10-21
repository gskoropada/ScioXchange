<div id="usrStats">
	<h1><?php echo $_SESSION['screenname']; ?></h1>
	<h3><?php echo "Your reputation is ".$_SESSION['reputation'];?></h3>
	<p>You have asked <span id="numOfQuest"></span> questions.</p>
	<p>You have received <span id="numOfAns"></span> answers.</p>
</div>
<div id="usrQuestions">
	<div id="dashOptions">
	<p>Questions | Answers | <a href="inbox.php" >Inbox</a> </p>
	</div>
</div>