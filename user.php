<?php
require_once ("../connectsodb.php");
require_once ("checksession.php");
userCheckPrivilege(1);

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
	echo $output;
?>
