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
		<input type="textarea" name="question" id="inQuestion">
	</p>
	<p>
		<label for="tags">Tags:</label>
		<input type="text" name="tags" id="inTags"><span id="tagSuggest"></span>
		</br>Multiple tags can be seperated with a comma and a space, like so: "tag1, tag2"
	</p>
	<p>
		<input type="button" name="submit" id="btnAsk" value="Ask!"><span id="msgArea"></span>
	</p>