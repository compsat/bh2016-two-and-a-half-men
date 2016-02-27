<?php
session_start();

$dbhost = "localhost";
$dbname = "bluehacks";
$dbuser = "root";
$dbpass = "therootisdead";

mysql_connect($dbhost, $dbuser, $dbpass) or die("MySQL Error: " . mysql_error());
mysql_select_Db($dbname) or die("MySQL Error: " . mysql_error());
?>