<div id="breadcrumbs">
	<p><a href="index.php">Home</a> > <a href="questions.php">Questions</a> > Ask</p>
</div>
<h2>Ask a Question!</h2>
	<p>
		<label for="title">Question Title:</label>
		<input type="text" name="title" id="inTitle">
	</p>
	<p>
		<label for="question_text">Question:</label>
		<textarea name="question" id="inQuestion" placeholder="Type your question here..."></textarea>
	</p>
	<p>
		<label for="tags">Tags </label><span class="tip">(Multiple tags can be seperated with a comma, like so: "tag1,tag2")</span>
		<input type="text" name="tags" id="inTags"><span id="tagSuggest"></span>
	</p>
	<p>
		<button name="submit" id="btnAsk">Ask!</button><span id="msgArea"></span>
	</p>