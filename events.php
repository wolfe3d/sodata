<?php
require_once  ("../connectsodb.php");
$query = "SELECT * from `eventtype`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$eventTypes="";
if($result)
{
	while ($row = $result->fetch_assoc()):
		$eventTypes.="<option value = '".$row['type']."'>".$row['type']."</option>";
	endwhile;
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Pragma" content="no-cache">
		<script src="../lib/jquery.js"></script>
		<script src="../lib/jquery.validate.min.js"></script>
	  <script type="text/javascript">

	$().ready(function() {
		$("#addTo").hide();
			// validate signup form on keyup and submit
		$("#addTo").validate({
			rules: {
				event_name: "required",
				type: "required",
			},
			messages: {
				event_name: "*Please enter the name event",
				type: "*Please enter the event type",
			},
			submitHandler: function(form) {
                form.submit();
            }
		});

	});
		</script>
	</head>
	<body>
	<button onclick="$('#addTo').show();$(this).hide();">Add</button>
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

</body>
</html>

<?php include('eventslist.php');?>
