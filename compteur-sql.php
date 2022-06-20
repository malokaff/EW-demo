
<br>
<?php
include("password.php");

//read from database
$mysqli = new mysqli("10.29.21.15", "root", "$pwd_sql", "MQTT");
if ($mysqli->connect_errno) {
	echo "<font color='FF000000' size='14'><b>";
	echo "failed to connect to database :"; 
	echo $mysqli->connect_error;
	echo "</b></font><br>";
}
else {
	$query = "SELECT * from `mqtt-value`";
	if ($result = $mysqli->query($query)) {
		while($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$value="$row[value]";
		}
	}
	echo "<br><font size='20'><b> From DB: $value</b></font><br>"; 
	}

?>
