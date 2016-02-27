<?php
	include "..\base.php";
	
	$username = $_SESSION['Username'];
	
	echo("Hello, " . $_SESSION['Username'] . "<br/>");

	//Gets the ID of the current user session
	$id_query = mysql_query("SELECT id FROM users WHERE username = '" . $username . "'");
	$id = mysql_fetch_array($id_query)['id'];

	//If a new game is submitted
	if(!empty($_POST['new_game']))
	{
		$game_name = mysql_real_escape_string($_POST['game_name']);
		$game_type = mysql_real_escape_string($_POST['game_type']);
		$game_data = mysql_real_escape_string($_POST['game_data']);

		//Make a new entry in the "games" table
		mysql_query("INSERT INTO games (name, user_id, type, high_score_id, high_score) VALUES(
			'" . $game_name . "',
			'" . $id . "',
			'" . $game_type . "',
			-1, 0
		)");
		
		//Get the id of the created entry
		$newgameid = mysql_fetch_array(mysql_query("SELECT id FROM games WHERE name = '" . $game_name . "'"))['id'];

		//Parse game data and place it in the "data" table, with the corresponding game_id
		foreach(explode("\\r\\n", $game_data) as $line)
		{	
			$line2 = explode(" ", $line);
			$insertrowquery = "INSERT INTO data (game_id, string_a, string_b) VALUES('". $newgameid ."', '". $line2[0] ."', '". $line2[1] ."')";

			$insertresult = mysql_query($insertrowquery) or die(mysql_error());
		}
		
		echo("Added " . $game_name . "<br/>");
	}
	
	//Uses that ID to retrieve the list of games
	$games_query = "SELECT * FROM games WHERE user_id = " . $id;
	$games = mysql_query($games_query);

	echo("Games<br/>");
	//Gets all the games
	while($row = mysql_fetch_array($games))
	{
		echo($row['name'] . "<br/>");
	}
	
	echo("Create new game<br/>");
	echo('<form method="POST" action="" name="new_game" id="new_game">');
	echo('	<label for="game_name">Game Name:</label><input type="text" name="game_name" id="game_name" /><br/>');
	echo('	<label for="game_type">Game Type:</label>
			<select name="game_type" id="game_type">
				<option value="match">Matching Type</option>
				<option value="category">Categories</option>
				<option value="order">Ordering</option>
				<option value="flashcards">Flashcards</option>
			</select><br/>');
	echo('	<label for="username">Game Name:</label><br/><textarea name="game_data" id="game_data" rows="4" cols="50"></textarea><br/>');
	echo('	<input type="submit" name="new_game" id="new_game" value="Create Game" />');
	echo('</form>');	
	
	echo("<a href=../logout.php>Logout</a>");
?>