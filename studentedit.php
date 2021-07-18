<?php
	require_once  ("../connectsodb.php");
	require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
	userCheckPrivilege(3);
	require_once  ("functions.php");


	//text output
	$output = "";

	$studentID = intval($_REQUEST['myID']);
	if(empty($studentID))
	{
		//no student id was sent, so initiate adding a student
		$defaultYear = date("Y")+4;
		$query = "INSERT INTO `student` (`studentID`, `userID`, `uniqueToken`, `last`, `first`, `active`, `yearGraduating`, `email`, `emailSchool`, `phoneType`, `phone`, `parent1Last`, `parent1First`, `parent1Email`, `parent1Phone`, `parent2Last`, `parent2First`, `parent2Email`, `parent2Phone`) VALUES (NULL, NULL, '', 'last_name', 'first_name', '1', '$defaultYear', '', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
		$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if ($result === TRUE)
		{
			$studentID =  $mysqlConn->insert_id;
		}
		else {
			exit("Failed to add new student.");
		}
	}

	//check to see if user has a valid studentID
	$query = "SELECT * FROM `student` WHERE `student`.`studentID` = $studentID";
	$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	//check to make sure the query was valid
	if(empty($result))
	{
		echo "Query Student Edit Failed.";
		exit();
	}

	//fill the row with the query data
	$row = $result->fetch_assoc();

	//Check permissions to make this user is either an admin or editing their own data
	/*if($_SESSION['userData']['privilege']<2 && $_SESSION['userData'][`id`]!=$row['userID'])
	{
		echo "The current user does not have privilege for this change.";
		exit;
	}*/
	userCheckPrivilege(2);

	//Check that student row exits from table
	if(!$row)
	{
		echo "No user found.";
		exit;
	}

	//find student's events
	$query = "SELECT * FROM `eventchoice` INNER JOIN `eventyear` ON `eventchoice`.`eventyearID`=`eventyear`.`eventyearID` INNER JOIN `event` ON `eventyear`.`eventID`=`event`.`eventID` WHERE `eventchoice`.`studentID`=$studentID ORDER BY `eventyear`.`year` DESC, `eventchoice`.`priority` ASC";
	$resultEventsChoice = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	echo $query;
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
	<input class="button fa" type="button" onclick="window.history.back()" value="&#xf0a8; Return" />

	<div id='eventAndPriority'>
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
