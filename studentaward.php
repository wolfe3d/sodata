<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

$studentID = intval($_POST['myID']);
$schoolID = $_SESSION['userData']['schoolID'];


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
?>

<form id="addTo" method="post" action="">
	<fieldset>
		<legend>Add Award</legend>
		<label for="awardName">Name of Award</label>
		<input id="awardName" name="awardName" class="form-control" type="text" value="">
	
		<label for="awardDate">Award Date</label>
		<input id="awardDate" name="awardDate" class="form-control" type="date" value="">
	
		<p id="tournaments">
			<?=getStudentsTournamentList($mysqlConn, $studentID, $schoolID)?>
		</p>

		<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
		<button class="btn btn-primary" onclick='addToSubmit("todo.php")' type="button"><span class='bi bi-save'></span> Save</button>

	</fieldset>
</form>

<?php
	studentAwards($mysqlConn, $studentID);
?>
