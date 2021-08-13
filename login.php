<?php
// Include configuration file
require_once  ("../connectsodb.php");
// Include Google API client library
require_once 'google-api-php-client/vendor/autoload.php';
// Call Google API
$gClient = new Google_Client();
$gClient->setClientId(GOOGLE_CLIENT_ID);
$gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
$gClient->setRedirectUri(GOOGLE_REDIRECT_URL);
$gClient->addScope(['email', 'profile']);
//$gClient->setScopes(array('https://www.googleapis.com/auth/plus.me', 'https://www.googleapis.com/auth/moderator'));

$authUrl = $gClient->createAuthUrl();
$output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><img src="images/btn_google_signin_dark_normal_web.png" alt="Sign in with Google"/></a>';
?>
<p>Login to team website using the google email that you registered with.  This has all the tournament, teammate and event information.</p>
<!-- Display login button / Google profile information -->
<p><?php echo $output; ?></p>
