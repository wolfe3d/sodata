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
		$("#addStudent").hide();
		$("#findStudent").hide();
		// validate signup form on keyup and submit
		$("#addStudent").validate({
			rules: {
				first: "required",
				last: "required",
				yearGraduating: "required",
				email: {
					required: true,
					email: true
				},
			},
			messages: {
				first: "*Please enter the student\'s first name",
				last: "*Please enter the student\'s last name",
				yearGraduating: {
					required: "*Enter the year the student is graduating",
				},
				email: {
					required: "*Enter the student\'s email.",
				},
			},
			submitHandler: function(form) {
                form.submit();
            }
		});
	});
</script>
	</head>
	<body>
	
	<button onclick="$('#findStudent').show();$(this).hide();">Search</button>
	<form id="findStudent" method="post" action="student.php">
		<fieldset>
			<legend>Find Student</legend>
			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text">
			</p>
			<p>
				<label for="last">Lastname</label>
				<input id="last" name="last" type="text">
			</p>
			</fieldset>
			<p>
				<input class="submit" type="submit" value="Submit">
			</p>
		</fieldset>
	</form>
	
	<button onclick="$('#addStudent').show();$(this).hide();">Add</button>
	<form id="addStudent" method="post" action="studentadd.php">
		<fieldset>
			<legend>Add Student</legend>
			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text">
			</p>
			<p>
				<label for="last">Lastname</label>
				<input id="last" name="last" type="text">
			</p>
			<p>
				<label for="yearGraduating">Year Graduating</label>
				<input id="yearGraduating" name="yearGraduating" type="text">
			</p>
			<p>
				<label for="email">Email</label>
				<input id="email" name="email" type="email">
			</p>
			<p>
				<label for="emailAlt">Alternate Email</label>
				<input id="emailAlt" name="emailAlt" type="email">
			</p>
			<p>
				<label for="phoneType">Phone Type</label>
				<select id="phoneType" name="text">
					<?=$phoneTypes?>
				</select>
			</p>
			<p>
				<label for="phone">Phone</label>
				<input id="phone" name="phone" type="tel">
			</p>
			<fieldset>
				<legend>Parent 1</legend>
				<p>
					<label for="parent1First">First</label>
					<input id="parent1First" name="parent1First" type="text">
				</p>
				<p>
					<label for="parent1Last">Last</label>
					<input id="parent1Last" name="parent1Last" type="text">
				</p>
				<p>
					<label for="parent1Email">Email</label>
					<input id="parent1Email" name="parent1Email" type="email">
				</p>
				<p>
					<label for="parent1Phone">Phone</label>
					<input id="parent1Phone" name="parent1Phone" type="tel">
				</p>
			</fieldset>
			<fieldset>
				<legend>Parent 2</legend>
				<p>
					<label for="parent2First">First</label>
					<input id="parent2First" name="parent2First" type="text">
				</p>
				<p>
					<label for="parent2Last">Last</label>
					<input id="parent2Last" name="parent2Last" type="text">
				</p>
				<p>
					<label for="parent2Email">Email</label>
					<input id="parent2Email" name="parent2Email" type="email">
				</p>
				<p>
					<label for="parent2Phone">Phone</label>
					<input id="parent2Phone" name="parent2Phone" type="tel">
				</p>
			</fieldset>
			<p>
				<input class="submit" type="submit" value="Submit">
			</p>
		</fieldset>
	</form>
	

<?php include("studentslist.php")?>

</body>
</html>