<?php
include "base.php";

$id = $_GET["id"];
$game_type = $_GET["game_type"];
$data = mysql_query("SELECT * FROM data WHERE game_id=" . $id);

//This one's for you, Julio
$stringerized_data = "";
$start = 1;
while($row = mysql_fetch_array($data))
{
	if($start == 1) {
		$start = 0;
		$stringerized_data .= $row['string_a'] . "_" . $row['string_b'];
	} else {
		$stringerized_data .= "|" . $row['string_a'] . "_" . $row['string_b'];
	}
}

echo("<script>");
echo("localStorage.setItem('BlueHacks-GameData','" . $stringerized_data . "');");
echo("</script>");
echo('<meta http-equiv="refresh" content="0;game/' . $game_type . '">');
?>