<?php
require_once  ("php/functions.php");
userCheckPrivilege(1);

$name = $mysqlConn->real_escape_string($_POST['tournamentName']);
$host = $mysqlConn->real_escape_string($_POST['host']);
$dateTournament = $mysqlConn->real_escape_string($_POST['dateTournament']);
$dateRegistration = $mysqlConn->real_escape_string($_POST['dateRegistration']);
$year = getIfSet($mysqlConn->real_escape_string($_POST['year']), getCurrentSOYear());
$type = intval($_POST['type']);
$numberTeams = intval($_POST['numberTeams']);
$weight = intval($_POST['weight']);
$teamsAttended = intval($_POST['teamsAttended']);
$note = $mysqlConn->real_escape_string($_POST['note']);
$address = $mysqlConn->real_escape_string($_POST['address']);
$addressBilling = $mysqlConn->real_escape_string($_POST['addressBilling']);
$websiteHost = $mysqlConn->real_escape_string($_POST['websiteHost']);
$websiteScilympiad = $mysqlConn->real_escape_string($_POST['websiteScilympiad']);
$director = $mysqlConn->real_escape_string($_POST['director']);
$directorEmail = $mysqlConn->real_escape_string($_POST['directorEmail']);
$directorPhone = $mysqlConn->real_escape_string($_POST['directorPhone']);


$query = "INSERT INTO `tournament` (`schoolID`,`tournamentName`,`host`,`dateTournament`,`dateRegistration`,`year`,`type`,`numberTeams`,`weight`,`teamsAttended`,`note`,`address`,`addressBilling`,`websiteHost`, `websiteScilympiad`, `director`, `directorEmail`, `directorPhone`) VALUES ('$schoolID','$name', '$host', '$dateTournament', '$dateRegistration', '$year', '$type', '$numberTeams', '$weight', '$teamsAttended', '$note', '$address', '$addressBilling', '$websiteHost', '$websiteScilympiad', '$director', '$directorEmail', '$directorPhone')";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result)
{
	$tournamentID = $mysqlConn->insert_id;
	//Add Teams
	$alphabet = range('A', 'Z');
	for ($n=0;$n<$numberTeams;$n++)
	{
		$teamName = $alphabet[$n];
		$query = "INSERT INTO `team` (`tournamentID`, `teamName`) VALUES ( '$tournamentID', '$teamName');";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	}
 	//Successful creation
	exit("$tournamentID");
}
else {
	error_log("Failed to add new tournament.");
	exit("Failed to add new tournament.");
}
?>
