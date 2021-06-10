<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);

$query = "SELECT * from `eventtype`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$eventTypes="";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$eventTypes.="<option value = '".$row['type']."'>".$row['type']."</option>";
	endwhile;
}

$query = "SELECT DISTINCT `year` FROM `eventyear`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$eventYears="";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$eventYears.="<option value = '".$row['year']."'>".$row['year']."</option>";
	endwhile;
}
?>
<div>
<input class="button fa" type="button" onclick="javascript:toggleSearch()" value="&#xf002; Find" />

	<div id="searchDiv">
	<form id="findEvent">
		<fieldset>
			<legend>Find Event by year</legend>
			<p>
				<label for="year">Year</label>
				<select id="year" name="year" type="text">
						<?=$eventYears?>
				</select>
			</p>
			<p>
				<input class="submit" type="submit" value="Find By Year">
			</p>
		</fieldset>
	</form>
</div>
<?php if(userHasPrivilege(3)){ ?>
	<input class="button fa" type="button" onclick="javascript:toggleAdd()" value="&#xf067; Add" />
	<form id="addTo" method="post" action="eventadd.php">
		<fieldset>
			<legend>Add Tournament</legend>
			<?php 	require_once  ("eventform.php"); ?>
			<input class="submit fa" type="submit" value="&#xf067; Add">
		</fieldset>
	</form>
	<input class="button fa" type="button" onclick="javascript:eventyearPreparePage()" value="&#xf133; Edit Year" />
<?php } ?>

<div id="list"></div>
</div>
