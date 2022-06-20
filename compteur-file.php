<br>
<?php
session_start();

//read from file
$data = file_get_contents("data.txt");
if($_SESSION["data"] == $data){
	echo "<font color='FF000000' size='20'><b>From file: no new data</b></font><br>";
}
else {
	echo "<font size='20'><b>From file: $data</b></font><br>";
}
$_SESSION["data"] = $data;

?>
