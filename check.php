<?php
// Database configuration
$mysqlConn= mysqli_connect('localhost', 'ggUs3963!er', '3DP2PuMsHwzXRpXR', 'scienceolympiad');

/* check connection */
if ($mysqlConn->connect_errno) {
   die("Failed to connect with MySQL: " . $mysqlConn->connect_error);
}
else {
		echo "okay";
}
?>
