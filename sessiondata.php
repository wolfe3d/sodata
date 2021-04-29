<?php
require_once ("../connectsodb.php");
 //Check to make sure user is logged in and has privileges
 // Include Google API client library
 //require_once 'google-api-php-client/vendor/autoload.php';
 // Call Google API
 /*$gClient = new Google_Client();
 $gClient->setClientId(GOOGLE_CLIENT_ID);
 $gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
 $gClient->setRedirectUri(GOOGLE_REDIRECT_URL);
 $gClient->addScope(['email', 'profile']);
 if(isset($_SESSION['token'])){
     $gClient->setAccessToken($_SESSION['token']);
 }
 */
function printUserData()
{
	foreach($_SESSION as $key=>$val){
			echo ($key.$val."<br>");
			foreach($val as $key2=>$val2){
				echo ($key2.":".$val2."<br>");
			}
	}
}
printUserData();

/*
if ($gClient->isAccessTokenExpired()) {
	echo "session expired";
	$output .="<div style='color:red'>Token Expired...Attempting to Refresh</div>";
	$gClient->fetchAccessTokenWithRefreshToken($gClient->getRefreshToken());
}
else {
	echo "session NOT expired";
}
*/

if(empty($_SESSION['token'])){
	// Remove token and user data from the session
	unset($_SESSION['token']);
	unset($_SESSION['userData']);

	// Reset OAuth access token
	if($gClient) $gClient->revokeToken();

	// Destroy entire session data
	session_destroy();

		//$gClient->setAccessToken($_SESSION['token']);
}
?>
