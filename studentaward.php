<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

$awardID = getIfSet(intval($_REQUEST['myID']?? null));
$schoolID = $_SESSION['userData']['schoolID'];

//check to make editing from student's own school occurs later

//get all the tournaments that the student has competed in
function getStudentsTournamentList($db, $studentID, $schoolID)
{
	$query = "SELECT `tournament`.`tournamentID`,`teamName`,`tournamentName`,`dateTournament` FROM `student` INNER JOIN `teammate` ON `student`.`studentID`=`teammate`.`studentID` INNER JOIN `team` ON `teammate`.`teamID`=`team`.`teamID` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID`  WHERE `student`.`schoolID`=$schoolID AND `student`.`studentID`=$studentID ORDER BY `dateTournament` DESC, `teamName`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="<div id='tournamentDiv'><label for='tournament'>Tournaments</label> ";
	$output .="<select class='form-select' id='tournament' name='tournament' required>";
	$output .= "<option value='0'>Not Associated With Tournament</option>";

	if($result && mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$output .= "<option value='".$row['tournamentID']."'>" . $row['tournamentName'] . " - ". $row['teamName'] ." (" . $row['dateTournament']  .")</option>";
		endwhile;
	}
	$output.="</select></div>";
	return $output;
}

$date = date('Y-m-d');
$row = NULL; 
$action = "javascript:addToSubmit('studentawardadd.php')";

if($awardID)
{
	//search for award and check to make sure the person is editing someone from their own school
	$query = "SELECT * FROM `award` INNER JOIN `student` ON `award`.`studentID`=`student`.`studentID` WHERE `awardID`=$awardID AND `schoolID`=" . $_SESSION['userData']['schoolID'];// where `field` = $fieldId";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($result)>0)
	{
		$row = $result->fetch_assoc();
		$action = "";
		$date = $row['awardDate'];
	}
}
?>

<form id="addTo" method="post" action="<?=$action?>">
	<fieldset>
		<p>
			<label for="student">Student</label>
			<?=getAllStudents($mysqlConn,0, $row['studentID'])?>
		</p>
		<p>
			<label for="awardName">Name of Award</label>
			<input id="awardName" name="awardName" class="form-control" type="text" value="<?=$row['awardName']?>">
		</p>
		<p>
			<label for="awardDate">Award Date</label>
			<input id="awardDate" name="awardDate" class="form-control" type="date" value="<?=$date?>">
		</p>
		<p>
			<label for="note">Note</label>
			<input id="note" name="note" class="form-control" type="text" value="<?=$row['note']?>">
		</p>
		<!--
		<p id="tournaments">
			<?//=getStudentsTournamentList($mysqlConn, $studentID, $schoolID)?>
		</p>
-->
		<p>
			<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
			<?php 	if(!$awardID){ ?>
				<button class='btn btn-primary' type='submit'><span class='bi bi-plus-circle'></span> Add</button>
			<?php } ?>
		</p>

	</fieldset>
</form>