<script>
<?php
include "base.php";
$data = mysql_query("SELECT * FROM data WHERE game_id=" . $id);

//This one's for you, Julio
$stringerized_data = "";
while($row = mysql_fetch_array($data))
{
	$stringerized_data = $stringerized_data . $row['string_a'] . "_" . $row['string_b'] . "|";
}
echo("localStorage['BlueHacks-GameData'] = " . $stringerized_data);
echo("window.location.href = game/" . $game_data);

?>
</script>