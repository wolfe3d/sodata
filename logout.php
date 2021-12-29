<?php
// Include configuration file
require_once '../connectsodb.php';

// Remove token and user data from the session
unset($_SESSION['token']);
unset($_SESSION['userData']);

// Reset OAuth access token
if(isset($gClient)) $gClient->revokeToken();

// Destroy entire session data
session_destroy();

// Redirect to homepage
header("Location:index.php#login");
//echo "User logged out";
?>
