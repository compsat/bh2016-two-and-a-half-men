<?php
	include "..\base.php";
	
	$username = $_SESSION['Username'];
	$user_games = array();

	if(!$username)
	{
		echo('<meta http-equiv="refresh" content="0;..">');
	}

	//Gets the ID of the current user session
	$get_ids = mysql_query("SELECT id FROM users WHERE username = '" . $username . "'");
	$id = $_SESSION['UserID'];

	//If a new game is submitted
	if(!empty($_POST['new_game']))
	{
		$game_name = mysql_real_escape_string($_POST['game_name']);
		$game_desc = mysql_real_escape_string($_POST['game_desc']);
		$game_category = mysql_real_escape_string($_POST['category']);
		$game_type = mysql_real_escape_string($_POST['game_type']);
		
		//Make a new entry in the "games" table
		mysql_query("INSERT INTO games (name, category, description, user_id, type, high_score_id, high_score) VALUES(
			'" . $game_name . "',
			'" . $game_category . "',
			'" . $game_desc . "',
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
	}
	
	//Uses that ID to retrieve the list of games
	$get_games = "SELECT * FROM games WHERE user_id = " . $id;
	$games = mysql_query($get_games);
	
	$gamelist = array();

	while($row = mysql_fetch_array($games))
	{
		array_push($gamelist, $row);
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="../css/alt-style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="../js/bootstrap.min.js"></script>		
	</head>
	
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<h1><center> <?php echo("Hello, " . $username); ?> <center></h1>
				</div>
				<div class="col-md-3">
					<a class="btn btn-info" href="../logout.php"><span style="font-size: 32px">Logout</span></a>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8" id="searchbox">
					<form role="form" method="POST" action="../search.php" name="search" id="search">
						<label for="search_query">Search:</label><input class="form-control" type="text" name="search_query" id="search_query" /><br/>
						<div class="radio-inline">
							<label><input type="radio" name="search_type" value="name" checked="checked">Name</label>
						</div>
						<div class="radio-inline">
							<label><input type="radio" name="search_type" value="user" checked="checked">User</label>
						</div>
						<div class="radio-inline">
							<label><input type="radio" name="search_type" value="category" checked="checked">Category</label>
						</div>
						<input class="btn btn-default" type="submit" name="search" id="search" value="Search" />
					</form>
				</div>
				<div class="col-md-2"></div>
			</div>
			<br/>			
			
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-4">
					<form role="form" method="POST" action="" name="new_game" id="new_game">
						<div class="form-group">
							<label for="game_name">Game Name:</label>
							<input class="form-control" type="text" name="game_name" id="game_name" />
						</div>
						
						<div class="form-group">
							<label for="game_type">Game Type:</label>
							<select class="form-control" oninput="updateRows()" name="game_type" id="game_type">
								<option value="matching">Matching Type</option>
								<option value="categories">Categories</option>
								<option value="ordering">Ordering</option>
							</select>
						</div>	
						<div class="form-group">
							<label for="category">Category:</label>
							<select class="form-control" name="category" id="category">
								<option value="history">History</option>
								<option value="math">Math</option>
								<option value="science">Science</option>
								<option value="english">English</option>
							</select>
						</div>
						<div class="form-group">
						<label for="game_data">Game Descripton:</label><br/><textarea class="form-control" name="game_desc" id="game_desc" rows="4" cols="50"></textarea><br/>
						</div>
						<label for="num_data">Number of rows:</label><input class="form-control" oninput="updateRows()" type="number" name="num_data" value="num_data" id="num_data" min="1"/><br/>
						<div id="rows" class="form-group">
						</div>
						<input class="btn btn-default" type="submit" name="new_game" id="new_game" value="Create Game" />
					</form>					
				</div>
				<div class="col-md-4">
					<h2>Your games</h2>
					<div class="list-group">
					<?php
						foreach($gamelist as $game)
						{
							echo("<a href='../startgame.php?id=". $game["id"] . "&game_type=" . $game['type']  . "'><button type='button' class='list-group-item btn-block'>". $game["name"] ."</button></a>");
						}
					?>
					</div>

				</div>
				<div class="col=md-2"></div>	
			</div>
		</div>
	
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
				row.class = "form-control";
				row.id = i;
				row.type = row.name = "row" + i;
				rows.appendChild(row);
				if(document.getElementById("game_type").value != "ordering")
				{
					i++;
					var row2 = document.createElement("input");
					row.class = "form-control";
					row2.id = i;
					row2.type = row2.name = "row" + i;
					rows.appendChild(row2);
				}
				rows.appendChild(document.createElement("br"));
			}
		}
		</script>		
	</body>
</html>