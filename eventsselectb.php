<?php
//this event select just gives the name of the event
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

//get list of events
$query = "SELECT * FROM `event` ORDER BY `event` ASC";
$resultEventsList = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$events ="<div id='eventsListDiv'><label for='eventsList'>Event</label> ";
$events .="<select id='eventsList' name='eventsList'>";
	if($resultEventsList)
	{
		while ($rowEvents = $resultEventsList->fetch_assoc()):
			$event = htmlspecialchars($mysqlConn->real_escape_string($rowEvents['event']));
			$type = $mysqlConn->real_escape_string($rowEvents['type']);
			$events .= "<option value='".$rowEvents['eventID']."'>$event - $type</option>";
		endwhile;
	}
	$events.="</select></div>";
echo $events;
?>
