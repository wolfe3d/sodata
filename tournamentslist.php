<?php
require_once ("php/functions.php");
userCheckPrivilege(1);

//text output
$output = "";

$search = isset($_POST['tournamentSearch'])?$mysqlConn->real_escape_string($_POST['tournamentSearch']):"";
$year = isset($_POST['year'])?intval($_POST['year']):getCurrentSOYear();

$query = "SELECT * from `tournament`";
//check to see what is searched for
$whereAND = "";
if($year)
{
	$whereAND .= " AND `tournament`.`year` LIKE '$year'";
}
if($search)
{
	$whereAND .= " AND (`tournament`.`tournamentName` LIKE '%$search%' 
	OR `tournament`.`note` LIKE '%$search%'
	OR `tournament`.`director` LIKE '%$search%'
	OR `tournament`.`host` LIKE '%$search%'
	OR `tournament`.`address` LIKE '%$search%'
	)";
}


$query .= " WHERE `schoolID` = $schoolID $whereAND ORDER BY `tournament`.`dateTournament` DESC";
$output .=userHasPrivilege(4)?$query:"";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$myTitle = $row['tournamentName']." - ".$row['year'];
		$output .="<div id='tournament-".$row['tournamentID']."'><hr><h2>".$myTitle."</h2>"; //start tournament output
		$output .="<div class='btn-group' role='group' aria-label='Tournament Buttons'>";
		$output .="<a class='btn btn-primary' role='button' href='#tournament-view-".$row['tournamentID']."'><span class='bi bi-journal'></span> Details</a>";
		$output .=isset($row['resultsLink'])?"<a class='btn btn-primary' target='_blank' role='button' href='".$row['resultsLink']."'><span class='bi bi-trophy'></span> Results</a>":"";
		$output .=userHasPrivilege(5)&&!$row['notCompetition']&&$row['dateTournament']<=date('Y-m-d')?"<a class='btn btn-primary' role='button' href='#tournament-score-".$row['tournamentID']."'><span class='bi bi-bar-chart'></span> Scores</a>":"";
		$output .="</div>";
		$output .=userHasPrivilege(5)&&!$row['published']?" <a class='btn btn-danger btn-sm' role='button' href='javascript:tournamentRemove(".$row['tournamentID'].",\"".$myTitle."\")'><span class='bi bi-eraser'></span> Remove</a>":"";


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
		$output .="<div>Weight/Difficulty (0-100, 50=local/small, 75=regional, 90=state, 100 is hardest=national level): ".$row['weight']."</div>";
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
			$output .="</div>"; //end tournament output
	endwhile;
	$output .="</div>";
}
echo $output;
?>
