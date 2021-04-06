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
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Pragma" content="no-cache">
	<script src="../lib/jquery.js"></script>
	<script src="../lib/jquery.validate.min.js"></script>
   <script type="text/javascript">
	function updateTrial(field,value)
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
			$("#"+field).parent().append(html);
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
			<legend>Add Student</legend>
			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text" value="<?=$row['first']?>" onchange="updateTrial(this.id,this.value)">
			</p>
			<p>
				<label for="last">Lastname</label>
				<input id="last" name="last" type="text" value="<?=$row['last']?>" onchange="updateTrial(this.id,this.value)">
			</p>
			<p>
				<label for="yearGraduating">Year Graduating</label>
				<input id="yearGraduating" name="yearGraduating" type="text" value="<?=$row['yearGraduating']?>" onchange="updateTrial(this.id,this.value)">
			</p>
			<p>
				<label for="email">Email</label>
				<input id="email" name="email" type="email" value="<?=$row['email']?>" onchange="updateTrial(this.id,this.value)">
			</p>
			<p>
				<label for="emailAlt">Alternate Email</label>
				<input id="emailAlt" name="emailAlt" type="email" value="<?=$row['emailAlt']?>" onchange="updateTrial(this.id,this.value)">
			</p>
			<p>
				<label for="phoneType">Phone Type</label>
				<select id="phoneType" name="text" value="<?=$row['phoneType']?>" onchange="updateTrial(this.id,this.value)">
					<?=$phoneTypes?>
				</select>
			</p>
			<p>
				<label for="phone">Phone</label>
				<input id="phone" name="phone" type="tel" value="<?=$row['phone']?>" onchange="updateTrial(this.id,this.value)">
			</p>
			<fieldset>
				<legend>Parent 1</legend>
				<p>
					<label for="parent1First">First</label>
					<input id="parent1First" name="parent1First" type="text" value="<?=$row['parent1First']?>" onchange="updateTrial(this.id,this.value)">
				</p>
				<p>
					<label for="parent1Last">Last</label>
					<input id="parent1Last" name="parent1Last" type="text" value="<?=$row['parent1Last']?>" onchange="updateTrial(this.id,this.value)">
				</p>
				<p>
					<label for="parent1Email">Email</label>
					<input id="parent1Email" name="parent1Email" type="email" value="<?=$row['parent1Email']?>" onchange="updateTrial(this.id,this.value)">
				</p>
				<p>
					<label for="parent1Phone">Phone</label>
					<input id="parent1Phone" name="parent1Phone" type="tel" value="<?=$row['parent1Phone']?>" onchange="updateTrial(this.id,this.value)">
				</p>
			</fieldset>
			<fieldset>
				<legend>Parent 2</legend>
				<p>
					<label for="parent2First">First</label>
					<input id="parent2First" name="parent2First" type="text" value="<?=$row['parent2First']?>" onchange="updateTrial(this.id,this.value)">
				</p>
				<p>
					<label for="parent2Last">Last</label>
					<input id="parent2Last" name="parent2Last" type="text" value="<?=$row['parent2Last']?>" onchange="updateTrial(this.id,this.value)">
				</p>
				<p>
					<label for="parent2Email">Email</label>
					<input id="parent2Email" name="parent2Email" type="email" value="<?=$row['parent2Email']?>" onchange="updateTrial(this.id,this.value)">
				</p>
				<p>
					<label for="parent2Phone">Phone</label>
					<input id="parent2Phone" name="parent2Phone" type="tel" value="<?=$row['parent2Phone']?>" onchange="updateTrial(this.id,this.value)">
				</p>
			</fieldset>
		</fieldset>
	</form>
</body>
</html>