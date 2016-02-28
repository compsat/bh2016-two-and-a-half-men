<?php
	include "base.php";
	
	$search_query = $_POST['search_query'];
	$search_type = $_POST['search_type'];
	$games = array();
	$back = "";
	
	$get_games = null;
	if(strcmp($search_type, "name") == 0)
	{
		$get_games = mysql_query("SELECT * FROM games WHERE name LIKE '%" . $search_query . "%'");
	}
	elseif(strcmp($search_type, "user") == 0)
	{
		$get_users = mysql_query("SELECT * FROM users WHERE username LIKE '%" . $search_query . "%'");
		if(mysql_num_rows($get_users) == 1)
		{
			$user_id = mysql_fetch_array($get_users)['id'];
			$get_games = mysql_query("SELECT * FROM games WHERE user_id=" . $user_id);
		}
	}
	
	if($get_games)
	{
		while($game = mysql_fetch_array($get_games))
		{
			$id = $game['id'];
			$user_id = $game['user_id'];
			$name = $game['name'];
			$type = $game['type'];

			$game['owner_name'] = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id='" . $user_id . "'"))['username'];
			
			array_push($games, $game);
		}
	}
	
	if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
	{
		$back = "dashboard";
	}
	else {
		$back = "index.php";
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/alt-style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>		
	</head>
	
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-3"></div>
				<div class="col-md-6">
					<h1><center> Search results for <?php echo($search_query); ?> <center></h1>
				</div>
				<div class="col-md-3">
					<a class="btn btn-info" href=<?php echo($back); ?>><span style="font-size: 32px">Back</span></a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8" id="searchbox">
					<form role="form" method="POST" action="search.php" name="search" id="search">
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
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<?php
					$count = count($games);
					if($count == 0)
					{
						echo("<h1>No search results found.</h1>");
					}
					elseif($count == 1) {
						echo("<h1>1 search result found.</h1>");
					}
					else {
						echo("<h1>". $count ." search results found.</h1>");
					}
					
					foreach($games as $game)
					{
						echo("<div style='padding: 5% 2% 5% 2%'>");
						echo("<a href='startgame.php?id=". $game['id'] . "&game_type=" . $game['type']  . "'><button type='button' class='list-group-item btn-block'>". $game["name"] ."</button></a>");
						echo("<br/><span style='padding-top:10%;'><strong>Category: </strong>". $game['category'] ."</span><br/>");
						echo("<br/><span><strong>Description: </strong>". $game['description'] ."</span>");
						echo("</div>");
					}
					?>
				</div>
				<div class="col-md-2"></div>
			</div>			
		</div>
	</body>
</html>