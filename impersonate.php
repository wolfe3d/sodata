<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

$userID = intval($_GET['userID']);
if($userID<5) {
	exit;
}

$query = "SELECT * from `user` WHERE `userID`=$userID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$users="<ul class='list-group'>";
if($result && mysqli_num_rows($result)>0)
{
	$userData = $result->fetch_assoc();


	// Include User library file
	require_once 'php/user.php';
	// Initialize User class
	$user = new User($mysqlConn);
	$userType = $user->checkUserType($userID, $userData['email'], true);
	//added to be able to use in session
	$userData['schoolID'] = $userType[0];
	$userData['type'] = $userType[1];
	$userData['active'] = $userType[2];

	// Storing user data in the session
	$_SESSION['userData'] = $userData;
	//sets variable to use in data.php so that we know we are impersonating
	$impersonate = 1;
	require_once ("data.php");
}
?>
