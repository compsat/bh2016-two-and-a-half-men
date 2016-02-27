<?php include "base.php"; ?>
<?php
if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
{
	echo('<meta http-equiv="refresh" content="0;dashboard">');
}
elseif(!empty($_POST['username']) && !empty($_POST['password']))
{
	$username = mysql_real_escape_string($_POST['username']);
	$password = md5(mysql_real_escape_string($_POST['password']));	
	
	if(!empty($_POST['register']))
	{
		$email = mysql_real_escape_string($_POST['email']);
		$query = "SELECT * FROM users WHERE username = '" . $username . "'";
		$checkusername = mysql_query($query);
		if(mysql_num_rows($checkusername) == 1)
		{
			echo("Sorry that username is taken");
			
			showLogin();
			showRegister();
		}
		else
		{
			$query = "INSERT INTO users (username, password, email) VALUES('" . $username . "', '" . $password . "', '" .$email."')";
			$registerquery = mysql_query($query) or die(mysql_error());
			
			if($registerquery)
			{
				echo("Registration successful. You can now log in.");
			}
			
			showLogin();
			showRegister();
		}
	}
	else
	{
		$query = "SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
		$checklogin = mysql_query("SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'");
		if(mysql_num_rows($checklogin) == 1)
		{
			$row = mysql_fetch_array($checklogin);
			$email = $row['email'];
			
			$_SESSION['Username'] = $username;
			$_SESSION['EmailAddress'] = $email;
			$_SESSION['LoggedIn'] = 1;
			
			echo('<meta http-equiv="refresh" content="0;dashboard">');
		}
		else
		{
			echo("Account not found!");
			showLogin();
			showRegister();
		}		
	}
}
else
{
	showLogin();
	showRegister();
}

function showLogin() {
	echo('<form method="POST" action="" name="login" id="login">');
	echo('	<label for="username">Username:</label><input type="text" name="username" id="username" /><br/>');
	echo('	<label for="password">Password:</label><input type="password" name="password" id="password" /><br/>');
	echo('	<input type="submit" name="login" id="login" value="Login" />');
	echo('</form>');
}

function showRegister() {
	echo('<form method="POST" action="" name="login" id="register">');
	echo('	<label for="username">Username:</label><input type="text" name="username" id="username" /><br/>');
	echo('	<label for="username">Email:</label><input type="text" name="email" id="email" /><br/>');
	echo('	<label for="password">Password:</label><input type="password" name="password" id="password" /><br/>');
	echo('	<input type="submit" name="register" id="register" value="Register" />');
	echo('</form>');
}
?>
