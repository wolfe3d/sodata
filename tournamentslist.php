<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

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
$output .=$_SESSION['userData']['privilege']>2?$query:"";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['name']." - ".$row['year']."</h2>";
		if($_SESSION['userData']['privilege']>2)
		{
			$output .="<div><a href='tournamentedit.php?tournamentID=".$row['tournamentID']."'>Edit</a></div>";
		}
		if($row['websiteHost'])
		{
			$output .="<div>Host: <a href='".$row['websiteHost']."'>".$row['host']."</a></div>";
		}
		else
		{
			$output .="<div>Host: ".$row['host']."</div>";
		}
		//Address of the tournament
		$output .="<div>Address: ".$row['address']."</div>";
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
			$output .="<div>Scilympiad Competition Website: <a href='".$row['websiteSciOly']."'>".$row['websiteSciOly']."</a></div>";
		}

		//Show Director information to coaches only
		if($_SESSION['userData']['privilege']>2)
		{
			//Director Information
			$output .="<h3>Director Information</h3>";
			$director=$row['director']?$row['director']:($row['directorEmail']?$row['directorEmail']:"");
			if($row['directorEmail'])
			{
				$director = "<a href='".$row['directorEmail']."'>$director</a>";
			}
			if($director)
			{
				$output .="<div>Director: $director</div>";
			}
			else if($row['directorEmail'])
			{
				$output .="<div>Host: ".$row['host']."</div>";
			}
			if($row['monthRegistration'])
			{
				$dateObj   = DateTime::createFromFormat('!m', $row['monthRegistration']);
				$monthName = $dateObj->format('F'); // March
				$output .="<div>Normal Month Registration: ".$monthName."</div>";
			}
		}

	endwhile;
	$output .="</div>";
}
echo $output;
?>
