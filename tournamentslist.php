<?php
require_once ("../connectsodb.php");
//text output
$output = "";

$name = $mysqlConn->real_escape_string($_POST['tournamentName']);
$year = intval($_POST['tournamentYear']);

$query = "SELECT * from `tournament` t1 INNER JOIN `tournamentInfo` t2 ON t1.`tournamentInfoID`=t2.`tournamentInfoID` ";
//check to see what is searched for
if($name&&$year)
{
	$query .= " where t2.`name` LIKE '$name' AND t1.`year` LIKE '$year'";
}
else if($name)
{
	$query .= " where t2.`name` LIKE '$name'";
}
else if($year)
{
	$query .= " where t1.`year` LIKE '$year' ";
}

$query .= " ORDER BY t2.`name` ASC";
$output .=$query;
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['name']." - ".$row['year']."</h2>";
		if($row['websiteHost'])
		{
			$output .="<div>Host: <a href='".$row['websiteHost']."'>".$row['host']."</a></div>";
		}
		else
		{
			$output .="<div>Host: ".$row['host']."</div>";
		}

		$output .="<div>Address: ".$row['address']."</div>";
		$output .="<div><a href='tournamentedit.php?tournamentID=".$row['tournamentID']."'>Edit</a></div>";
		$output .="<div>Date Tournament: ".$row['dateTournament']."</div>";
		$output .="<div>Date Registration: ".$row['dateRegistration']."</div>";
		$output .="<div>Number of Teams Registered: ".$row['numberTeams']."</div>";
		$output .="<div>Weighting/Diffuculty (0-100, 50=local/small, 75=regional, 90=state, 100 is hardest=national level): ".$row['weighting']."</div>";
		if($row['type'])
		{
			$output .="<div>Type:".$row['type']."</div>";
		}
		if($row['note'])
		{
			$output .="<div>Note:".$row['note']."</div>";
		}
		if($row['websiteSciOly'])
		{
			$output .="<div>SciOly Competition Website: <a href='".$row['websiteSciOly']."'>".$row['websiteSciOly']."</a></div>";
		}
		//Address of the tournament

		//If coach show the following information
		$output .="<h3> Director Information</h3>";
		if($row['director'])
		{
			$output .="<div>Director:".$row['director']."</div>";
		}
		if($row['dateRegistration'])
		{
			$output .="<div>Normal Registration Date:".$row['dateRegistrationNormal']."</div>";
		}
	endwhile;
	$output .="</div>";
}
echo $output;
?>
