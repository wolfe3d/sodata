<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

function allAwards()
{
	global $mysqlConn;
	$output = "";
	$query = "SELECT * FROM `award` INNER JOIN `student` ON `award`.`studentID`=`student`.`studentID` WHERE `schoolID` = " . $_SESSION['userData']['schoolID'] . " ORDER BY `awardDate` DESC, `last`, `first`";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$output .= "<div id='award-" . $row['awardID'] . "'><span class='award'>" . $row['awardDate'] . " ". $row['last'] .", " .$row['first']."  <strong>" . $row['awardName']   . "</strong> ". $row['note'] ."</span>";
			$output .= userHasPrivilege(4)?" <a class='btn btn-warning' role='button' href='#student-award-".$row['awardID']."'><span class='bi bi-pencil-square'></span> Edit</a>":"";
			$output .= userHasPrivilege(5)?" <a class='btn btn-danger btn-sm' role='button' href='javascript:studentAwardRemove(" . $row['awardID'] .")'><span class='bi bi-eraser'></span> Remove</a>":"";
			$output .= "</div>";
		endwhile;
	}
	return $output;
}
?>
	<p>
        <a class="btn btn-primary" role="button" href="#student-award"><span class='bi bi-plus-circle'></span> Add Award</a>
	</p>
<?php
echo allAwards();
?>
	<p>
		<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
        <a class="btn btn-primary" role="button" href="#student-award"><span class='bi bi-plus-circle'></span> Add Award</a>
	</p>