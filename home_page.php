<!--Main page content-->
<div id="wrapper">
	<div id="question_slider"></div>
	<div id="info_pane">
		<div id="home_content">
		<?php 
		require ("connect.php");
		
		$query = "SELECT count(question_id) as q FROM question";
		$result = mysqli_query($con, $query);
	
		$q = mysqli_fetch_array($result);
		
		echo "<p> $q[0] questions have been asked</p>";
		
		$query = "SELECT count(answer_id) as a FROM answer";
		$result = mysqli_query($con, $query);
		
		$a = mysqli_fetch_array($result);
		
		echo "<p>That received $a[0] answers ";
		
		$query = "SELECT count(comment_id) as c FROM comment";
		$result = mysqli_query($con, $query);
		
		$c = mysqli_fetch_array($result);
		
		echo "and $c[0] comments</p>";
		?>	
		</div>
		<div id="wordcloud" ></div>
	</div>
</div>

