<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(3);
require_once  ("functions.php");


//text output
$output = "";

$tournamentID = intval($_REQUEST['myID']);
if(empty($tournamentID))
{
	//no tournament id was sent, so initiate adding a tournament
	$defaultYear = date("Y")+4;
	//TODO: ADD tournament if it does not exist
	//$query = "INSERT INTO `tournament` (`tournamentID`, `userID`, `uniqueToken`, `last`, `first`, `active`, `yearGraduating`, `email`, `emailSchool`, `phoneType`, `phone`, `parent1Last`, `parent1First`, `parent1Email`, `parent1Phone`, `parent2Last`, `parent2First`, `parent2Email`, `parent2Phone`) VALUES (NULL, NULL, '', 'last_name', 'first_name', '1', '$defaultYear', '', NULL, 'cell', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)";
	//$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if ($result === TRUE)
	{
		$tournamentID =  $mysqlConn->insert_id;
	}
	else {
		echo "Failed to add new tournament.";
		exit();
	}
}

//check to see if user has a valid tournamentID
$query = "SELECT * from `tournament` WHERE `tournament`.`tournamentID` = $tournamentID";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

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
			<p>
				<label for="tournamentName">Name</label>
				<input id="tournamentName" name="tournamentName" type="text" value="<?=$row['tournamentName']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="host">Host</label>
				<input id="host" name="host" type="text" value="<?=$row['host']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="address">Address</label>
				<input id="address" name="address" type="text" value="<?=$row['address']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="dateTournament">Competition Date</label>
				<input id="dateTournament" name="dateTournament" type="date" value="<?=$row['dateTournament']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="dateRegistration">Registration Date</label>
				<input id="dateRegistration" name="dateRegistration" type="date" value="<?=$row['dateRegistration']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="year">Competition Year (National Rules Year)</label>
				<!--//TODO: Make this a selection -->
				<input id="year" name="year" type="number" value="<?=$row['year']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<!--//TODO: Make this a selection -->
				<label for="type">Type of Competition (Full, Mini, Hybrid, etc.)</label>
				<input id="type" name="type" type="text" value="<?=$row['type']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="numberTeams">Number of Teams Registered</label>
				<input id="numberTeams" name="numberTeams" type="number" value="<?=$row['numberTeams']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="weighting">Weighting</label>
				<input id="weighting" name="weighting" type="number" value="<?=$row['weighting']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="note">Note(s)</label>
				<input id="note" name="note" type="text" value="<?=$row['note']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="websiteHost">Host's Website</label>
				<input id="websiteHost" name="websiteHost" type="text" value="<?=$row['websiteHost']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="websiteScilympiad">Host's Scilympiad Site</label>
				<input id="websiteScilympiad" name="websiteScilympiad" type="text" value="<?=$row['websiteScilympiad']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="note">Note(s)</label>
				<input id="note" name="note" type="text" value="<?=$row['note']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="director">Director(s)</label>
				<input id="director" name="director" type="text" value="<?=$row['director']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="directorEmail">Director's Email</label>
				<input id="directorEmail" name="directorEmail" type="text" value="<?=$row['directorEmail']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="directorPhone">Director's Phone</label>
				<input id="directorPhone" name="directorPhone" type="text" value="<?=$row['addressBilling']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
			<p>
				<label for="addressBilling">Address Billing</label>
				<input id="addressBilling" name="addressBilling" type="text" value="<?=$row['addressBilling']?>" onchange="fieldUpdate(<?=$tournamentID?>,'tournament',this.id,this.value)">
			</p>
		</fieldset>
		<?=$privilegeText ?>
	</form>
	<input class="button fa" type="button" onclick="window.history.back()" value="&#xf0a8; Return" />

	</div>
