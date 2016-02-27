<?php 
	include "base.php";
	$_SESSION = array(); 
	session_destroy(); 
	echo('<meta http-equiv="refresh" content="0;.">');
?>