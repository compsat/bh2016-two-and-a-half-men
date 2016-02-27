<?php
	include "..\base.php";
	
	$username = $_SESSION['Username'];
	
	echo("Hello, " . $_SESSION['Username'] . "<br/>");

	//Gets the ID of the current user session
	$get_ids = mysql_query("SELECT id FROM users WHERE username = '" . $username . "'");
	$id = $_SESSION['UserID'];

	//If a new game is submitted
	if(!empty($_POST['new_game']))
	{
		$game_name = mysql_real_escape_string($_POST['game_name']);
		$game_type = mysql_real_escape_string($_POST['game_type']);
		
		//Make a new entry in the "games" table
		mysql_query("INSERT INTO games (name, user_id, type, high_score_id, high_score) VALUES(
			'" . $game_name . "',
			'" . $id . "',
			'" . $game_type . "',
			-1, 0
		)");
		
		//Get the id of the created entry
		$newgameid = mysql_fetch_array(mysql_query("SELECT id FROM games WHERE name = '" . $game_name . "'"))['id'];
		
		$num_data = $_POST['num_data'];
		if(strcmp($game_type, "ordering") != 0)
		{
			$num_data *= 2;
		}
		
		for($i = 0; $i < $num_data; $i++)
		{
			if(strcmp($game_type, "ordering") == 0)
			{
				$a = $_POST["row" . $i];
				echo($a);
				$insertrowquery = "INSERT INTO data (game_id, string_a, string_b) VALUES('". $newgameid ."', '". $a ."', ' ')";
			}
			else 
			{
				$a = $_POST["row" . $i++];
				$b = $_POST["row" . $i];

				$insertrowquery = "INSERT INTO data (game_id, string_a, string_b) VALUES('". $newgameid ."', '". $a ."', '". $b ."')";
			}
			$insertresult = mysql_query($insertrowquery) or die(mysql_error());
		}

		echo("Added " . $game_name . "<br/>");
	}
	
	//Uses that ID to retrieve the list of games
	$get_games = "SELECT * FROM games WHERE user_id = " . $id;
	$games = mysql_query($get_games);

	echo("<b>Games</b><br/>");
	//Gets all the games
	while($row = mysql_fetch_array($games))
	{
		echo($row['name'] . "<br/>");
	}
?>
<b>Create new game</b><br/>
<form method="POST" action="" name="new_game" id="new_game">
	<label for="game_name">Game Name:</label><input type="text" name="game_name" id="game_name" /><br/>
	<label for="game_type">Game Type:</label>
		<select oninput="updateRows()" name="game_type" id="game_type">
			<option value="match">Matching Type</option>
			<option value="category">Categories</option>
			<option value="ordering">Ordering</option>
			<option value="flashcards">Flashcards</option>
		</select><br/>
	<label for="category">Category:</label>
	<select name="category" id="category">
		<option value="history">History</option>
		<option value="math">Math</option>
		<option value="science">Science</option>
		<option value="english">English</option>
	</select><br/>
	<label for="game_data">Game Descripton:</label><br/><textarea name="game_desc" id="game_desc" rows="4" cols="50"></textarea><br/>
	<label for="num_data">Number of rows:</label><input oninput="updateRows()" type="number" name="num_data" value="num_data" id="num_data" min="1"/><br/>
	<div id="rows">
		
	</div>
	<input type="submit" name="new_game" id="new_game" value="Create Game" />
</form>

<div id="searchbox">
	<form method="POST" action="../search.php" name="search" id="search">
		<label for="search_query">Search:</label><input type="text" name="search_query" id="search_query" /><br/>
		<input type="radio" name="search_type" value="name" checked="checked">Name</input>
		<input type="radio" name="search_type" value="user">User</input>
		<input type="radio" name="search_type" value="category">Category</input>
		<input type="submit" name="search" id="search" value="Search" />
	</form>
</div>	

<a href=../logout.php>Logout</a>

<script>
function updateRows() {
	var n = parseInt(document.getElementById("num_data").value);
	
	var rows = document.getElementById("rows");
	var br = document.createElement("br");
	
	while (rows.hasChildNodes()) {
		rows.removeChild(rows.lastChild);
	}
	
	if(document.getElementById("game_type").value != "ordering")
	{
		n *= 2;
	}
	
	for(var i = 0; i < parseInt(n) ; i++) {
		
		var row = document.createElement("input");
		row.id = i;
		row.type = row.name = "row" + i;
		rows.appendChild(row);
		if(document.getElementById("game_type").value != "ordering")
		{
			i++;
			var row2 = document.createElement("input");
			row2.id = i;
			row2.type = row2.name = "row" + i;
			rows.appendChild(row2);
		}
		rows.appendChild(document.createElement("br"));
	}
}
</script>