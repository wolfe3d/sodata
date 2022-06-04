<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

//text output
$output = "";

$coachID = intval($_REQUEST['myID']);

//check to see if user has a valid CoachID
$query = "SELECT * FROM `coach` WHERE `coach`.`coachID` = ".$coachID;
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$row = $result->fetch_assoc();

//Check permissions to make this user is either an admin or editing their own data
if(!userHasPrivilege(4) && $_SESSION['userData'][`id`]!=$row['userID'])
{
	echo "The current user does not have privilege for this change.";
	exit;
}

//Check that student row exits from table
if(!$row)
{
	echo "No user found.";
	exit;
}
$privilegeText = editPrivilege(4,$row['userID'],$mysqlConn);
?>
<form id="studentUpdate" method="post" action="studentUpdate.php">
		<fieldset>
			<legend>Edit Student</legend>
			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text" value="<?=$row['first']?>" onchange="studentUpdate(<?=$coachID?>,'coach',this.id,this.value)">
			</p>
			<p>
				<label for="last">Lastname</label>
				<input id="last" name="last" type="text" value="<?=$row['last']?>" onchange="studentUpdate(<?=$coachID?>,'coach',this.id,this.value)">
			</p>
			<p>
				<label for="position">Position</label>
				<input id="position" name="position" type="text" value="<?=$row['position']?>" onchange="studentUpdate(<?=$coachID?>,'coach',this.id,this.value)">
			</p>
			<p>
				<!--Changing Google Email may break functions TODO: Think about changing this ability-->
				<label for="email">Google Email</label>
				<input id="email" name="email" type="email" value="<?=$row['email']?>" onchange="studentUpdate(<?=$coachID?>,'coach',this.id,this.value)">
			</p>
			<p>
				<label for="emailSchool">School Email</label>
				<input id="emailSchool" name="emailSchool" type="email" value="<?=$row['emailSchool']?>" onchange="studentUpdate(<?=$coachID?>,'coach',this.id,this.value)">
			</p>
		</fieldset>
		<?=$privilegeText ?>
	</form>
<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='fa fa-arrow-circle-left'></span> Return</button></p>
