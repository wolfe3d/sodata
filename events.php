<?php
require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once ("php/functions.php");

$query = "SELECT * from `eventtype`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$eventTypes="";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$eventTypes.="<option value = '".$row['type']."'>".$row['type']."</option>";
	endwhile;
}

/*
//Replaced by GetSOYears
$query = "SELECT DISTINCT `year` FROM `eventyear` ORDER BY `year` DESC";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$eventYears="<option value = '0'>All</option>";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$eventYears.="<option value = '".$row['year']."'>".$row['year']."</option>";
	endwhile;
}
*/
?>
<div>
<button class="btn btn-secondary" type="button" onclick="javascript:toggleSearch()"><span class='fa'>&#xf002;</span> Find</button> <!-- toggles view of below div -->

	<div id="searchDiv">
	<form id="findEvent">
		<fieldset>
			<legend>Find Event by year</legend>
			<p>
				<label for="year">Year</label>
				<?=getSOYears("",1)?>
			</p>
			<p>
				<button class="btn btn-primary" type="submit"><span class='fa'>&#xf073;</span> Find By Year</button>
			</p>
		</fieldset>
	</form>
</div>
<?php if(userHasPrivilege(3)){ ?>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleAdd()"><span class='fa'>&#xf067;</span> Add</button>

	<form id="addTo" method="post" action="eventadd.php">
		<fieldset>
			<legend>Add Event</legend>
			<?php 	require_once  ("eventform.php"); ?>
			<button class="btn btn-primary" type="submit"><span class='fa'>&#xf067;</span> Add</button>
		</fieldset>
	</form>
	<a class='btn btn-secondary' role='button' href='#eventyear-edit-<?=getCurrentSOYear();?>'><span class='fa'>&#xf133;</span> Edit Year</a>
	<a class='btn btn-secondary' role='button' href='#events-analysis-<?=getCurrentSOYear();?>'><span class='fa'>&#xf200;</span> Analysis</a>
<?php } ?>

<div id="list"></div>
</div>
