<?php
require_once  ("../connectsodb.php");
// require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges

$query = "SELECT * from `eventtype`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$eventTypes="";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$eventTypes.="<option value = '".$row['type']."'>".$row['type']."</option>";
	endwhile;
}

$query = "SELECT DISTINCT `year` FROM `eventsyear`";
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
	<a href="javascript:toggleSearch()">Find</a>
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

<a href="javascript:toggleAdd()">Add</a>
	<form id="addTo" method="post" action="eventadd.php">
		<fieldset>
			<legend>Add Event</legend>
			<p>
				<label for="event_name">Event</label>
				<input id="event_name" name="event_name" type="text">
			</p>
			<!-- <p>
				<label for="type">Type</label>
				<input id="type" name="type" type="type">
			</p> -->
			<p>
				<label for="type">Event Type</label>
				<select id="type" name="type" type="text">
						<?=$eventTypes?>
				</select>
			</p>
		</fieldset>
		<fieldset>
			<p>
				<input class="submit" type="submit" value="Submit">
			</p>
		</fieldset>
	</form>


<div id="list"></div>
</div>
