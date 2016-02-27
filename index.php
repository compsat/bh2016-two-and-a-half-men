<?php 
	include "base.php";
	
	//If a session already is ongoing
	if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
	{
		echo('<meta http-equiv="refresh" content="0;dashboard">');
	}
	//If the user sent a form (either login or registration)
	elseif(!empty($_POST['username']) && !empty($_POST['password']))
	{
		$username = mysql_real_escape_string($_POST['username']);
		$password = md5(mysql_real_escape_string($_POST['password']));	
		
		//If the user registered
		if(!empty($_POST['register']))
		{
			$email = mysql_real_escape_string($_POST['email']);
			$query = "SELECT * FROM users WHERE username = '" . $username . "'";
			$checkusername = mysql_query($query);
			if(mysql_num_rows($checkusername) == 1)
			{
				echo("Sorry that username is taken");
			}
			else
			{
				$query = "INSERT INTO users (username, password, email) VALUES('" . $username . "', '" . $password . "', '" .$email."')";
				$registerquery = mysql_query($query) or die(mysql_error());
				
				if($registerquery)
				{
					echo("Registration successful. You can now log in.");
				}
			}
		}
		//If the user is logging in
		else
		{
			$query = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
			$checklogin = mysql_query("SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'");
			if(mysql_num_rows($checklogin) == 1)
			{
				$row = mysql_fetch_array($checklogin);
				$email = $row['email'];
				
				$_SESSION['Username'] = $username;
				$_SESSION['UserID'] = $row['id'];
				$_SESSION['EmailAddress'] = $email;
				$_SESSION['LoggedIn'] = 1;
				
				echo('<meta http-equiv="refresh" content="0;dashboard">');
			}
			else
			{
				echo("Account not found!");
			}		
		}
	}

	echo('<div id="loginbox">');
	echo('	<form method="POST" action="" name="login" id="login">');
	echo('		<label for="username">Username:</label><input type="text" name="username" id="username" /><br/>');
	echo('		<label for="password">Password:</label><input type="password" name="password" id="password" /><br/>');
	echo('		<input type="submit" name="login" id="login" value="Login" />');
	echo('	</form>');
	echo('</div>');

	echo('<div id="registerbox">');
	echo('	<form method="POST" action="" name="register" id="register">');
	echo('		<label for="username">Username:</label><input type="text" name="username" id="username" /><br/>');
	echo('		<label for="username">Email:</label><input type="text" name="email" id="email" /><br/>');
	echo('		<label for="password">Password:</label><input type="password" name="password" id="password" /><br/>');
	echo('		<input type="submit" name="register" id="register" value="Register" />');
	echo('	</form>');
	echo('</div>');
	
	echo('<div id="searchbox">');
	echo('	<form method="POST" action="search.php" name="search" id="search">');
	echo('		<label for="search_query">Search:</label><input type="text" name="search_query" id="search_query" /><br/>');
	echo('		<input type="radio" name="search_type" value="name" checked="checked">Name</input>');
	echo('		<input type="radio" name="search_type" value="user">User</input>');
	echo('		<input type="radio" name="search_type" value="category">Category</input>');
	echo('		<input type="submit" name="search" id="search" value="Search" />');
	echo('	</form>');
	echo('</div>');

?>