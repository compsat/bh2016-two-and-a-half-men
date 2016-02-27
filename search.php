<?php
	include "base.php";
	
	$search_query = $_POST['search_query'];
	$search_type = $_POST['search_type'];
	
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
			$id = $game['user_id'];
			$username = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id='" . $game['user_id'] . "'"))['username'];
				
			echo($game['name'] . ", by " . $username . "<br/>");	
		}
	}
	
	if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
	{
		echo('<a href="dashboard">Back to Homepage</a>');
	}
	else {
		echo('<a href="index.php">Back to Homepage</a>');
	}
?>