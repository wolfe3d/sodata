<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
require_once  ("php/remove.php"); //Check to make sure user is logged in and has privileges
require_once("php/functions.php");

userCheckPrivilege(5);
$schoolID = $_SESSION['userData']['schoolID'];
$teamID = intval($_POST['myID']);
if($teamID)
{
	$tournamentID = getTournamentID($teamID);
	if(checkNotSchoolID($schoolID,"tournament","tournamentID",$tournamentID)){
		exit ("You do not have permission to remove this team.");
	}
	//Checks to make sure no students are assigned to team
	if(checkinTable('teammate','teamID',$teamID))
	{
		exit ("Assignments exist for this team");
	}

//Remove team
deletefromTable("team","teamID",$teamID);

exit ("1");
}

exit ("Team ID not sent.");
?>
