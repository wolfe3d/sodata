<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
//text output
$output = "";

$name = isset($_POST['tournamentName'])?$mysqlConn->real_escape_string($_POST['tournamentName']):"";
$year = isset($_POST['tournamentYear'])?intval($_POST['tournamentYear']):0;

$query = "SELECT * from `tournament`";
//check to see what is searched for
if($name&&$year)
{
	$query .= " where `tournament`.`tournamentName` LIKE '$name' AND `tournament`.`year` LIKE '$year'";
}
else if($name)
{
	$query .= " where `tournament`.`tournamentName` LIKE '$name'";
}
else if($year)
{
	$query .= " where `tournament`.`year` LIKE '$year' ";
}

$query .= " ORDER BY `tournament`.`tournamentName` ASC";
$output .=userHasPrivilege(4)?$query:"";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$myTitle = $row['tournamentName']." - ".$row['year'];
		$output .="<hr><h2>".$myTitle."</h2>";
		$output .="<input class='button fa' type='button' onclick='window.location.hash=\"tournament-view-".$row['tournamentID']."\"' value='&#xf108; View Details' />";

		if($row['websiteHost'])
		{
			$output .="<div>Host: <a href='".$row['websiteHost']."'>".$row['host']."</a></div>";
		}
		else
		{
			$output .="<div>Host: ".$row['host']."</div>";
		}
		//Address of the tournament
		if($row['address']){
			$output .="<div>Address: <a href='https://www.google.com/maps/search/?api=1&query=".$row['address']."'>".$row['address']."</a></div>";
		}
		$output .="<div>Date Tournament: ".$row['dateTournament']."</div>";
		$output .="<div>Number of Teams Registered: ".$row['numberTeams']."</div>";
		$output .="<div>Weighting/Difficulty (0-100, 50=local/small, 75=regional, 90=state, 100 is hardest=national level): ".$row['weighting']."</div>";
		if($row['type'])
		{
			switch ($row['type']){
			case 1:
				$output .="<div>Type: Full</div>";
				break;
			case 2:
				$output .="<div>Type: Mini</div>";
				break;
			case 3:
				$output .="<div>Type: Hybrid</div>";
				break;
			}
		}
		if($row['note'])
		{
			$output .="<div>Note: ".$row['note']."</div>";
		}
		if($row['websiteScilympiad'])
		{
			$output .="<div>Scilympiad Competition Website: <a href='".$row['websiteScilympiad']."'>".$row['websiteScilympiad']."</a></div>";
		}
	endwhile;
	$output .="</div>";
}
echo $output;
?>
