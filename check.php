<?php
// Database configuration
$mysqlConn= mysqli_connect('localhost', 'ggUs3963!er', '3DP2PuMsHwzXRpXR', 'scienceolympiad');

/* check connection */
if ($mysqlConn->connect_errno) {
   die("Failed to connect with MySQL: " . $mysqlConn->connect_error);
}
else {

		echo "okay";
		$query = "SELECT * FROM `coach` WHERE `coach`.`coachID` = 1";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$row = $result->fetch_assoc();
		echo $row['first'];
		echo "okay2";
}
?>
