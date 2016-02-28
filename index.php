<?php 
include "base.php";

$message = "";
$reg_success = '<div class="row">
						<div class="col-md-8"></div>
						<div class="alert alert-success alert-dismissible fade in">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
   								 <span aria-hidden="true">&times;</span>
 							 </button>
							<strong>Success!</strong> Registration successful.
						</div>
						<div class="col-md-8"></div>
					</div>';
			
$username_taken = '<div class="row">
						<div class="col-md-8"></div>
						<div class="alert alert-danger alert-dismissible fade in">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>Failure!</strong> Username already taken.
						</div>
						<div class="col-md-8"></div>
					</div>';
					
$account_not_found = '<div class="row">
						<div class="col-md-8"></div>
						<div class="alert alert-danger alert-dismissible fade in">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>Failure!</strong> Incorrect username or password.
						</div>
						<div class="col-md-8"></div>
					</div>';

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
			$message = $username_taken;
		}
		else
		{
			$query = "INSERT INTO users (username, password, email) VALUES('" . $username . "', '" . $password . "', '" .$email."')";
			$registerquery = mysql_query($query) or die(mysql_error());
			$message = $reg_success;
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
			$message = $account_not_found;
		}		
	}
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
		<div id="main" class="container">
			<?php
				echo($message);
			?>
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-4" id="loginbox">
					<form role="form" method="POST" action="" name="login" id="login">
						<div class="form-group">
							<label for="username">Username:</label>
							<input class="form-control" type="text" name="username" id="username" />
						</div>
						<div class="form-group">
							<label for="password">Password:</label>
							<input class="form-control" type="password" name="password" id="password" />
						</div>
						<input class="btn btn-default" type="submit" name="login" id="login" value="Login" />
					</form>
				</div>

				<div class="col-md-4" id="registerbox">
					<form role="form" method="POST" action="" name="register" id="register">
						<div class="form-group">
							<label for="username">Username:</label>
							<input class="form-control" type="text" name="username" id="username" />
						</div>
						
						<div class="form-group">
							<label for="username">Email:</label>
							<input class="form-control" type="text" name="email" id="email" />
						</div>
						
						<div class="form-group">
							<label for="password">Password:</label>
							<input class="form-control" type="password" name="password" id="password" />
						</div>
						
						<input class="btn btn-default" type="submit" name="register" id="register" value="Register" />
					</form>
				</div>
				<div class="col-md-2"></div>
			</div>
			<br/><br/>
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
		</div>
	</body>
</html>