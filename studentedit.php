<?php
require_once  ("../connectsodb.php");
//text output
$output = "";

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

$studentID = intval($_REQUEST['studentID']);

$query = "SELECT * from `students` WHERE `studentID`=$studentID ";// where `field` = $fieldId";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$row = $result->fetch_assoc();
}

//find student's events
$query = "SELECT * FROM `eventschoice` t1 INNER JOIN `eventsyear` t2 ON t1.`eventID`=t2.`eventID` WHERE `studentID`=$studentID ";// where `field` = $fieldId";
$resultEventsChoice = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
$eventsChoice ="";
if($resultEventsChoice)
{
	while ($rowEventsChoice = $resultEventsChoice->fetch_assoc()):
		$eventsChoice .= "<div id='eventChoice-" . $rowEventsChoice['eventsChoiceID'] . "'>" . $rowEventsChoice['year'] . " " . $rowEventsChoice['event'] . " <a href='' onclick=\"removeEvent('" . $rowEventsChoice['eventsChoiceID'] . "');return false;\">Remove</a></div>";
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
		 $("#eventsList").hide();
	 });
	 function addEventChoice()
 	{
		$("#eventsList").clone().appendTo("#addEventsDiv").show();
		$("#addEventsDiv").append("<a id='addThisEvent' onclick='addEvent(this.id,this.value); return false;' href=''>Add</a>");
	}
	function removeEvent(value)
	{
		// validate signup form on keyup and submit
		var request = $.ajax({
			url: "studenteventremove.php",
			cache: false,
			method: "POST",
			data: { eventsChoiceID: value}, //TODO: must add priority
			dataType: "html"
		});

		request.done(function( html ) {
			//$("label[for='" + field + "']").append(html);
			$(".modified").remove(); //removes any old update notices
			if (html=="1")
			{
				//returns the current update
				$("#eventChoice-"+value).remove();
				$("#events").append("<span class='modified' style='color:blue'>Event removed.</span>");
			}
			else
			{
				$("#events").append("<span class='modified' style='color:red'>Error while attempting to remove an event. Please, report details to site admin.</span>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			alert( "Request failed: " + textStatus );
		});
	}
	function addEvent(field,value)
	{
		// validate signup form on keyup and submit
		var request = $.ajax({
			url: "studenteventadd.php",
			cache: false,
			method: "POST",
			data: { studentID: <?=$studentID?>, eventID : $("#eventsList").val(), priority : "1" }, //TODO: must add priority
			dataType: "html"
		});

		request.done(function( html ) {
			//$("label[for='" + field + "']").append(html);
			$(".modified").remove(); //removes any old update notices
			if (html=="1")
			{
				//returns the current update
				$("#events").append("<div>"+ $("#eventsList option:selected").text() + " <span class='modified' style='color:blue'>Event added.</span></div>");
			}
			else
			{
				$("#events").append("<span class='modified' style='color:red'>Error while attempting to add an event. Please, report details to site admin.</span>");
			}
		});

		request.fail(function( jqXHR, textStatus ) {
			alert( "Request failed: " + textStatus );
		});
	}
	function updateStudent(field,value)
	{
		// validate signup form on keyup and submit
		var request = $.ajax({
			url: "studentupdate.php",
			cache: false,
			method: "POST",
			data: { studentID: <?=$studentID?>, myfield : field, myvalue : value },
			dataType: "html"
		});

		request.done(function( html ) {
			//$("label[for='" + field + "']").append(html);
			$(".modified").remove(); //removes any old update notices
			$("#"+field).parent().append("<span class='modified' style='color:blue'>"+ html +"</span>"); //returns the current update
		});

		request.fail(function( jqXHR, textStatus ) {
			alert( "Request failed: " + textStatus );
		});
	}
</script>
	</head>
	<body>
<form id="studentUpdate" method="post" action="studentUpdate.php">
		<fieldset>
			<legend>Edit Student</legend>
			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text" value="<?=$row['first']?>" onchange="updateStudent(this.id,this.value)">
			</p>
			<p>
				<label for="last">Lastname</label>
				<input id="last" name="last" type="text" value="<?=$row['last']?>" onchange="updateStudent(this.id,this.value)">
			</p>
			<p>
				<label for="yearGraduating">Year Graduating</label>
				<input id="yearGraduating" name="yearGraduating" type="text" value="<?=$row['yearGraduating']?>" onchange="updateStudent(this.id,this.value)">
			</p>
			<p>
				<label for="email">Email</label>
				<input id="email" name="email" type="email" value="<?=$row['email']?>" onchange="updateStudent(this.id,this.value)">
			</p>
			<p>
				<label for="emailAlt">Alternate Email</label>
				<input id="emailAlt" name="emailAlt" type="email" value="<?=$row['emailAlt']?>" onchange="updateStudent(this.id,this.value)">
			</p>
			<p>
				<label for="phoneType">Phone Type</label>
				<select id="phoneType" name="text" value="<?=$row['phoneType']?>" onchange="updateStudent(this.id,this.value)">
					<?=$phoneTypes?>
				</select>
			</p>
			<p>
				<label for="phone">Phone</label>
				<input id="phone" name="phone" type="tel" value="<?=$row['phone']?>" onchange="updateStudent(this.id,this.value)">
			</p>
			<fieldset>
				<legend>Events</legend>
				<div id="events"><?=$eventsChoice?></div>
				<div id="addEventsDiv">
				<?php
					if($resultEvents)
					{
						$rowEvents = $result->fetch_assoc();
					}
				?>
			</div>
				<a id="addEvent" onclick="addEventChoice();$(this).hide();return false;" href="">Add Event</a>
			</fieldset>
			<fieldset>
				<legend>Parent 1</legend>
				<p>
					<label for="parent1First">First</label>
					<input id="parent1First" name="parent1First" type="text" value="<?=$row['parent1First']?>" onchange="updateStudent(this.id,this.value)">
				</p>
				<p>
					<label for="parent1Last">Last</label>
					<input id="parent1Last" name="parent1Last" type="text" value="<?=$row['parent1Last']?>" onchange="updateStudent(this.id,this.value)">
				</p>
				<p>
					<label for="parent1Email">Email</label>
					<input id="parent1Email" name="parent1Email" type="email" value="<?=$row['parent1Email']?>" onchange="updateStudent(this.id,this.value)">
				</p>
				<p>
					<label for="parent1Phone">Phone</label>
					<input id="parent1Phone" name="parent1Phone" type="tel" value="<?=$row['parent1Phone']?>" onchange="updateStudent(this.id,this.value)">
				</p>
			</fieldset>
			<fieldset>
				<legend>Parent 2</legend>
				<p>
					<label for="parent2First">First</label>
					<input id="parent2First" name="parent2First" type="text" value="<?=$row['parent2First']?>" onchange="updateStudent(this.id,this.value)">
				</p>
				<p>
					<label for="parent2Last">Last</label>
					<input id="parent2Last" name="parent2Last" type="text" value="<?=$row['parent2Last']?>" onchange="updateStudent(this.id,this.value)">
				</p>
				<p>
					<label for="parent2Email">Email</label>
					<input id="parent2Email" name="parent2Email" type="email" value="<?=$row['parent2Email']?>" onchange="updateStudent(this.id,this.value)">
				</p>
				<p>
					<label for="parent2Phone">Phone</label>
					<input id="parent2Phone" name="parent2Phone" type="tel" value="<?=$row['parent2Phone']?>" onchange="updateStudent(this.id,this.value)">
				</p>
			</fieldset>
		</fieldset>
	</form>
<?php include("eventsselect.php")?>
</body>
</html>
