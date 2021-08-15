<?php
require_once ("../connectsodb.php");
require_once ("checksession.php");
require_once("functions.php");
userCheckPrivilege(1);
$output = "";
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['id']);
$currentYear = getCurrentSOYear();
if(!empty($_SESSION['userData'])){
  //$output     = '<h2>Google Account Details</h2>';
  $output .= '<div class="ac-data">';
	$output .="<p style=' text-align: center'><img src='images/teamphoto.jpg' alt='team photo' width='600px'><p>";
	$output .= '<p>You are logged in to Walton Science Olympiad Team Website!</p>';
  $output .= '<img src="'.$_SESSION['userData']['picture'].'">';
  //$output .= '<p><b>Google ID:</b> '.$userData['oauth_uid'].'</p>';
  $output .= '<p><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
  $output .= '<p><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';
  //$output .= '<p><b>Gender:</b> '.$userData['gender'].'</p>';
  //$output .= '<p><b>Locale:</b> '.$userData['locale'].'</p>';

//Student Reminders and Results
	$query = "SELECT * FROM `student` WHERE `userID` = ".$_SESSION['userData']['id'];
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result->num_rows){
		$row = $result->fetch_assoc();
		//TODO: Show new tournaments signups with links to tournament pages, priority of events with links to events, previous tournament results.
		$output .= "<h2>Upcoming Tournaments</h2>";
		include("upcomingtournaments.php");
		$output .= $tournaments;
		$output .= "<h2>Event Priority</h2><ol>";

		$priorityQuery = "SELECT `priority`, `event` FROM `eventchoice` inner join `eventyear` on `eventchoice`.`eventyearID` = `eventyear`.`eventyearID` inner join `event` on `eventyear`.`eventID` = `event`.`eventID` where `eventchoice`.`studentID` = $studentID and `year` = $currentYear";
		$result = $mysqlConn->query($priorityQuery) or error_log("\n<br />Warning: query failed:$priorityQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		while ($row = $result->fetch_assoc()):
			$output.="<li>".$row['event']."</li>";
		endwhile;
		$output .= "</ol><h2>Previous Results</h2>";
		include("results.php");
		$output .= $tournaments;
	}

	//Coach Reminders and Results
		$query = "SELECT * FROM `coach` WHERE `userID` = ".$_SESSION['userData']['id'];
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if($result->num_rows){
			$row = $result->fetch_assoc();
			//TODO: Show new tournaments signups with link
			$output .= "<h2>Upcoming Tournaments</h2>";
			$output .= "<p>Add upcoming tournament information. Coming Soon..This website is a work in progress.  Currently, you can find all tournament information in the tournament tab above.</p>";
			$output .= "<h2>Recent Tournaments</h2>";
			$output .= "<p>Add recent tournament information</p>";
		}

  $output .= '<p>Logout from <a href="logout.php">Google</a></p>';
  $output .= '</div>';
}else{
  $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
}
	echo $output;
?>
