<?php
	require_once  ("php/functions.php");
	userCheckPrivilege(3);

	//text output
	$output = "";
	$studentID = intval($_REQUEST['myID']);

	//check to see if user has a valid studentID
	$query = "SELECT * FROM `student` WHERE `student`.`studentID` = $studentID";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	//check to make sure the query was valid
	if(empty($result))
	{
		echo "Query Student Edit Failed.";
		exit();
	}

	//fill the row with the query data
	$row = $result->fetch_assoc();

	//Check permissions to make this user is either an admin or editing their own data
	userCheckPrivilege(2);

	//Check that student row exists om table
	if(!$row)
	{
		echo "No user found.";
		exit;
	}

	//find student's events
	$query = "SELECT * FROM `eventchoice` INNER JOIN `eventyear` ON `eventchoice`.`eventyearID`=`eventyear`.`eventyearID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `eventchoice`.`studentID`=$studentID ORDER BY `eventyear`.`year` DESC, `eventchoice`.`priority` ASC";
	$resultEventsChoice = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$eventsChoice ="";
	if(mysqli_num_rows($resultEventsChoice)>0)
	{
		$eventsChoice .="<div>Year-Priority Event Name</div>";
		while ($rowEventsChoice = $resultEventsChoice->fetch_assoc()):
			$eventsChoice .= "<div id='eventchoice-" . $rowEventsChoice['eventchoiceID'] . "'><span class='event'>" . $rowEventsChoice['year'] . "-" . $rowEventsChoice['priority'] . " " . $rowEventsChoice['event'] . "</span> <a href=\"javascript:studentEventRemove('" . $rowEventsChoice['eventchoiceID'] . "')\">Remove</a></div>";
		endwhile;
	}

	$privilegeText = editPrivilege(4,$row['userID'],$mysqlConn);
?>
<form id="addTo" method="post" action="fieldUpdate.php">
		<fieldset>
			<legend>Edit Student</legend>
			<?php require_once  ("studentform.php"); ?>
		</fieldset>
		<?=$privilegeText ?>
	</form>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='fa fa-arrow-circle-left'></span> Return</button></p>	<div id='eventAndPriority'>
		<?php include("eventsselect.php")?>
		<div id="priority">
			<label for="priorityList">Priority</label>
			<select id='priorityList'>
				<option value='1'>1 - Highest</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5 - Lowest</option>
			</select>
		</div>
	</div>
	<?php include("courseselect.php")?>
