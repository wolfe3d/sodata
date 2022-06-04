<?php
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once  ("php/functions.php");

$name = $mysqlConn->real_escape_string($_POST['tournamentName']);
$host = $mysqlConn->real_escape_string($_POST['host']);
$dateTournament = $mysqlConn->real_escape_string($_POST['dateTournament']);
$dateRegistration = $mysqlConn->real_escape_string($_POST['dateRegistration']);
$year = getIfSet($mysqlConn->real_escape_string($_POST['year']), getCurrentSOYear());
$type = intval($_POST['type']);
$numberTeams = intval($_POST['numberTeams']);
$weight = intval($_POST['weight']);
$note = $mysqlConn->real_escape_string($_POST['note']);
$address = $mysqlConn->real_escape_string($_POST['address']);
$addressBilling = $mysqlConn->real_escape_string($_POST['addressBilling']);
$websiteHost = $mysqlConn->real_escape_string($_POST['websiteHost']);
$websiteScilympiad = $mysqlConn->real_escape_string($_POST['websiteScilympiad']);
$director = $mysqlConn->real_escape_string($_POST['director']);
$directorEmail = $mysqlConn->real_escape_string($_POST['directorEmail']);
$directorPhone = $mysqlConn->real_escape_string($_POST['directorPhone']);

$query = "INSERT INTO `tournament` (`tournamentName`,`host`,`dateTournament`,`dateRegistration`,`year`,`type`,`numberTeams`,`weight`,`note`,`address`,`addressBilling`,`websiteHost`, `websiteScilympiad`, `director`, `directorEmail`, `directorPhone`) VALUES ('$name', '$host', '$dateTournament', '$dateRegistration', '$year', '$type', '$numberTeams', '$weight', '$note', '$address', '$addressBilling', '$websiteHost', '$websiteScilympiad', '$director', '$directorEmail', '$directorPhone')";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result)
{
	echo $mysqlConn->insert_id;
}
else {
	exit("Failed to add new tournament.");
}
?>
