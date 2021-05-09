<?php
require_once ("../connectsodb.php");
require_once ("functions.php");
 //Check to make sure user is logged in and has privileges

 // Include Google API client library
 require_once 'google-api-php-client/vendor/autoload.php';
 // Call Google API
 $gClient = new Google_Client();
 $gClient->setClientId(GOOGLE_CLIENT_ID);
 $gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
 $gClient->setRedirectUri(GOOGLE_REDIRECT_URL);
 $gClient->addScope(['email', 'profile']);

 if(isset($_SESSION['token'])){
     $gClient->setAccessToken($_SESSION['token']);
 }

 if($gClient->getAccessToken()){
    checkGoogle($gClient,$mysqlConn);

		if ($gClient->isAccessTokenExpired()) {
			$output .="<div style='color:red'>Token Expired...Attempting to Refresh</div>";
		  $gClient->fetchAccessTokenWithRefreshToken($gClient->getRefreshToken());
		}
    // Render user profile data
    if(!empty($_SESSION['userData'])){
        //$output     = '<h2>Google Account Details</h2>';
        $output .= '<div class="ac-data">';
				$output .= '<p>You are logged in to Walton Science Olympiad Database!</p>';
        $output .= '<img src="'.$_SESSION['userData']['picture'].'">';
        //$output .= '<p><b>Google ID:</b> '.$userData['oauth_uid'].'</p>';
        $output .= '<p><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
        $output .= '<p><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';
        //$output .= '<p><b>Gender:</b> '.$userData['gender'].'</p>';
        //$output .= '<p><b>Locale:</b> '.$userData['locale'].'</p>';
        $output .= '<p>Logout from <a href="logout.php">Google</a></p>';
        $output .= '</div>';
    }else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
	}else{
 	 header("Location:index.php#login");
  }
	echo $output;
?>
