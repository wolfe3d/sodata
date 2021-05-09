<?php
//this event select just gives the name of the event
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

//get list of events
$query = "SELECT * FROM `event` ORDER BY `event` ASC";// where `field` = $fieldId";
$resultEventsList = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$events ="<div id='eventsListDiv'><label for='eventsList'>Event</label> ";
$events .="<select id='eventsList' name='eventsList'>";
	if($resultEventsList)
	{
		while ($rowEvents = $resultEventsList->fetch_assoc()):
			$events .= "<option value='" . $rowEvents['event'] . "'>" . $rowEvents['event'] . " - " . $rowEvents['type']  ."</option>";
		endwhile;
	}
	$events.="</select></div>";
echo $events;
?>
