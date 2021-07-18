<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
require_once ("functions.php");
userCheckPrivilege(1);

/*check to see if id exists*/
$query = "SELECT * from `phonetype`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$phoneTypes="";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$phoneTypes.="<option value = '".$row['phoneType']."'>".$row['phoneType']."</option>";
	endwhile;
}

// add student button ---> studentEdit() function
// $addStudentText ="";
// if(userHasPrivilege(2))
// {
// 	$addStudentText .="<input class='button fa' type='button' onclick='javascript:studentEdit()' value='&#xf067; Add' />";
// }

// // output emails
// if(userHasPrivilege(2))
// {
// 	$addStudentText .=" <input class='button fa' type='button' onclick='location.href=\"emails.php\"' value='&#xf01c; Get Emails' />";
// }

?>
<div>
	<input class="button fa" type="button" onclick="javascript:toggleSearch()" value="&#xf002; Find" />
	<?php if(userHasPrivilege(2))
	{ ?>
	<input class="button fa" type="button" onclick="javascript:toggleAdd()" value="&#xf067; Add" />
	<form id="addTo" method="post" action="studentadd.php">
		<fieldset>
			<legend>Add Student</legend>
			<?php require_once("studentform.php"); ?>
		</fieldset>
		<input class="submit fa" type="submit" value="&#xf067; Add" />
	</form>
	<input class='button fa' type='button' onclick='location.href="emails.php"' value='&#xf01c; Get Emails' />
	<?php }?>
<br><br>
	<form id="findStudent">
		<div>
			<input type="checkbox" id="active" name="active" value="1" checked>
			<label for="active">Show only active students</label>
		</div>
		<div id="searchDiv">
		<fieldset>
			<legend>Find Student By name</legend>
			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text">
			</p>
			<p>
				<label for="last">Lastname</label>
				<input id="last" name="last" type="text">
			</p>
			<p>
				<input class="submit" type="submit" value="Find By Name">
			</p>
		</fieldset>
	</form>
	<form id="findByEventPriority">
		<fieldset>
			<legend>Find Students by Event That They Signed Up For</legend>
			<p>
				<?=getEventList($mysqlConn, 0,"Event Priority")?>
			</p>
			<p>
				<input class="submit" type="submit" value="Find By Event">
			</p>
		</fieldset>
	</form>
	<form id="findByEventCompetition">
		<fieldset>
			<legend>Find Students by Event That They Have Competed In</legend>
			<p>
				<?=getEventList($mysqlConn, 1,"Events Competed")?>
			</p>
			<p>
				<input class="submit" type="submit" value="Find By Event">
			</p>
		</fieldset>
	</form>
	<form id="findByCourse">
		<fieldset>
			<legend>Find Students by Coursework</legend>
			<p>
				<?php include("courseselect.php")?>
			</p>
			<p>
				<input class="submit" type="submit" value="Find By Course">
			</p>
		</fieldset>
		</div>
	</form>
<div id="list"></div>
</div>
