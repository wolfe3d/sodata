<?php
require_once ("../connectsodb.php");
require_once ("checksession.php");
require_once("functions.php");
userCheckPrivilege(1);
$output = "";
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$currentYear = getCurrentSOYear();
$fallRosterDate = strval(getCurrentSOYear()-1)."-08-01";
if(!empty($_SESSION['userData'])){
  //$output     = '<h2>Google Account Details</h2>';
  $output .= '<div class="ac-data">';
	//$output .="<p style=' text-align: center'><img src='images/teamphoto.jpg' alt='team photo' width='600px'><p>";
	$output .= '<p style="text-align:center"><iframe src="https://docs.google.com/presentation/d/e/2PACX-1vQBp-90QI1zuFDF7zy7oI76ytDFJ2r_-w8oIz7R-w7BLCrZuci-93x1QEnRpwvJPjM8U3-Z9RC4gMTv/embed?start=true&loop=true&delayms=5000" frameborder="0" width="960" height="569" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe></p>';
	$output .= '<p>You are logged in to Walton Science Olympiad Team Website!</p>';
  $output .= '<img src="'.$_SESSION['userData']['picture'].'">';
  //$output .= '<p><b>Google ID:</b> '.$userData['oauth_uid'].'</p>';
  $output .= '<p><b>Name:</b> '.$_SESSION['userData']['first_name'].' '.$_SESSION['userData']['last_name'].'</p>';
  $output .= '<p><b>Email:</b> '.$_SESSION['userData']['email'].'</p>';
  //$output .= '<p><b>Gender:</b> '.$userData['gender'].'</p>';
  //$output .= '<p><b>Locale:</b> '.$userData['locale'].'</p>';

  $output .= "<h2> Quick Links </h2><p>";
  $output .= "<a href='https://drive.google.com/file/d/13gIkPawogKlDHzhNBfTPgQ5hi045QDiv/view?usp=sharing'> 2022 Official Rules Manual </a><br>";
  $output .= "<a href='data.php#tournament-view-12'> 2022 Fall Semester Teams </a><br>";
  $output .= "<a href='https://drive.google.com/drive/folders/17LMINQEqhEP3IQzT8jj1-3Iw6gt8boRI?usp=sharing'> Digital Test Bank </a><br>";
  $output .= "<a href='https://calendar.google.com/calendar/embed?src=waltonscienceclub%40gmail.com&ctz=America%2FNew_York'> Google Calendar </a></p>";

//TODO: Fallrosterdate should be changed in the table to indicate that this is a roster instead of tournaments
//TODO: Remove all warnings in tournamentview for a roster

//Student Reminders and Results
	if($studentID!=0)
	{
		//Show new tournaments signups with links to tournament pages, priority of events with links to events, previous tournament results.
		$output .= "<h2>Upcoming Tournaments</h2>";
		include("tournamentupcoming.php");
		$output .= $tournaments;
		$output .= "<h2>My Events</h2><h3> Fall Events: </h3>";
		$fallEventsQuery = "SELECT `event` FROM `teammateplace` INNER JOIN `student` on `teammateplace`.`studentID` = `student`.`studentID` INNER JOIN `tournamentevent` on `teammateplace`.`tournamenteventID` = `tournamentevent`.`tournamenteventID` inner join `event` on `tournamentevent`.`eventID` = `event`.`eventID` where `tournamentID` = 12 and `student`.`studentID` = $studentID";
		$result = $mysqlConn->query($fallEventsQuery) or error_log("\n<br />Warning: query failed:$fallEventsQuery. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		$output .= "<ul>";
		while ($row = $result->fetch_assoc()):
			$output.="<li>".$row['event']."</li>";
		endwhile;
		$output .= "</ul>";

		$output .= studentEventPriority($mysqlConn, $studentID);
		$output .= studentTournamentResults($mysqlConn, $studentID);
	}

	//Coach Reminders and Results
		$query = "SELECT * FROM `coach` WHERE `userID` = ".$_SESSION['userData']['userID'];
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
