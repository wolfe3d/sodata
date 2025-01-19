<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/remove.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
$schoolID = $_SESSION['userData']['schoolID'];

$meetingID = intval($_POST['myID']);
if($meetingID)
{
	//Checks before deletion to prevent damaging of database?

	//Remove student from all tables
	deletefromTable("meeting",'meetingID',$meetingID);
	deletefromTable("meetingattendance",'meetingID',$meetingID);

	// Check if it's an AJAX request for attendance refresh
	// if (isset($_POST['refreshAttendance']) && $_POST['refreshAttendance'] == '1') {
    // // Process the attendance data and return the relevant output for AJAX request

	// 	$output = "<hr><h2 id='meetings'>Meetings</h2>";
	// 	$output .= "<div id='meetingList'><br>";

	// 	$query = "SELECT * FROM `meeting` WHERE `meeting`.`eventID` = $eventID ORDER BY `meeting`.`meetingDate` DESC";
	// 	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	// 	while ($row = $result->fetch_assoc()):
	// 		if ($row['meetingTypeID'] == 1 && $row['eventID'] == $eventID) {
	// 			$output .= "<h3>" . $row['meetingDate'] . "</h3>";
	// 			$timeDifference = time() - strtotime($row['meetingDate'] . $row['meetingTimeOut']);
	// 			if (userHasPrivilege(2) && ($timeDifference <= 86400)) {
	// 				$meetingID = $row['meetingID'];
	// 				$output .= "<a class='btn btn-warning btn-sm' role='button' href='#attendance-edit-$meetingID'><span class='bi bi-pencil-square'></span> Edit Meeting</a>";
	// 				$output .= "<a class='btn btn-danger btn-sm' role='button' href='javascript:attendanceRemove(" . $row['meetingID'] . ")'><span class='bi bi-eraser'></span> Remove</a>";
	// 			} elseif (userHasPrivilege(5)) {
	// 				$meetingID = $row['meetingID'];
	// 				$output .= "<a class='btn btn-warning btn-sm' role='button' href='#attendance-edit-$meetingID'><span class='bi bi-pencil-square'></span> Edit Meeting</a>";
	// 			}
	// 			$output .= "<li>Time In: " . date('h:i A', strtotime($row['meetingTimeIn'])) . "</li>";
	// 			$output .= "<li>Time Out: " . date('h:i A', strtotime($row['meetingTimeOut'])) . "</li>";
	// 			$output .= "<li>Description: " . $row['meetingDescription'] . "</li>";
	// 			$output .= "<li>Homework: " . $row['meetingHW'] . "</li></ul>";
	// 		}
	// 	endwhile;
	// 	$output .= "</div>";

	// 	echo $output;
	// }


	exit ("1");
}
exit ("Meeting ID not sent.");
?>
