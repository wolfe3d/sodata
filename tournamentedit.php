<?php
require_once  ("php/functions.php");
userCheckPrivilege(3);

//text output
$output = "";

$tournamentID = intval($_POST['myID']);
if(empty($tournamentID))
{
 exit("Tournament does not exist!");
}

//check to see if user has a valid tournamentID
$query = "SELECT * from `tournament` WHERE `tournament`.`tournamentID` = $tournamentID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

//check to make sure the query was valid
if(empty($result))
{
	echo "Query Student Edit Failed.";
	exit();
}

//fill the row with the query data
$row = $result->fetch_assoc();
//Check that tournament row exits from table
if(!$row)
{
	echo "No user found.";
	exit;
}

?>
<div id='myTitle'><?=$row['tournamentName']?> - <?=$row['year']?></div>
	<form id="addTo" method="post" action="tournamentUpdate.php">
		<fieldset>
			<legend>Edit Tournament</legend>
			<?php require_once("tournamentform.php"); ?>
		</fieldset>
	</form>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='fa fa-arrow-circle-left'></span> Return</button></p>
</div>
