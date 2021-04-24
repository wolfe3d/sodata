<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges


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
		<script src="js/jquery-3.6.0.min.js"></script>
		<script src="js/jquery.validate.min.js"></script>
	  <script type="text/javascript">

	$().ready(function() {
		$("#addTo").hide();
		$("#searchDiv").hide();
		//Load Students
		getList({active: +$("#active").is(':checked')});
			// validate signup form on keyup and submit
		$("#addTo").validate({
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

		//if the active checkbox is changed, then the screen will repopulate with the entire science olympiad population.  It does not remember the last clicked search.
		$('#active').change(function() {
        getList({active: +$("#active").is(':checked')});
    });
		//when Find by Name is clicked, this initiates the search
		$("#findStudent").on( "submit", function( event ) {
  		event.preventDefault();
  		getList( $( this ).serialize() );
		});
		//when Find by Event is clicked, this initiates the search
		$("#findByEvent").on( "submit", function( event ) {
			event.preventDefault();
			getList( {eventsList: $("#eventsList").val()});
		});
		//when Find by Course is clicked, this initiates the search
		$("#findByCourse").on( "submit", function( event ) {
			event.preventDefault();
			getList( {coursesList: $("#coursesList").val()});
		});
	});
	function getList(myData)
	{
		//alert(JSON.stringify(myData) );
		//myData is a json object type
		var request = $.ajax({
		 url: "studentslist.php",
		 cache: false,
		 method: "POST",
		 data: myData,
		 dataType: "html"
		});
		request.done(function( html ) {
		 //$("label[for='" + field + "']").append(html);
		 $("#list").html(html);
		});

		request.fail(function( jqXHR, textStatus ) {
		 $("#list").html("Search Error");
		});
		<?php
		if ($_SESSION['userData']['privilege']>2)
		{
			?>
		function studentRemove(myStudentID)
		{
			//alert(JSON.stringify(myData) );
			//myData is a json object type

			var request = $.ajax({
			 url: "studentremove.php",
			 cache: false,
			 method: "POST",
			 data: {studentID:myStudentID},
			 dataType: "html"
			});
			request.done(function( html ) {
			 //$("label[for='" + field + "']").append(html);
			 $("#list").html(html);
			});

			request.fail(function( jqXHR, textStatus ) {
			 $("#list").html("Removal Error");
			});
			<?php } ?>
	}
</script>
	</head>
		<body id="top">
		<div>
			<input type="checkbox" id="active" name="active" value="1" checked>
			<label for="active">Show only active students</label>
		</div>
	<button onclick="$('#searchDiv').show();$(this).hide();">Search</button>
	<div id="searchDiv">
	<form id="findStudent">
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
	<form id="findByEvent">
		<fieldset>
			<legend>Find Students by Event That They Signed Up For</legend>
			<p>
				<?php include("eventsselectb.php")?>
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
				<?php include("coursesselect.php")?>
			</p>
			<p>
				<input class="submit" type="submit" value="Find By Course">
			</p>
		</fieldset>
	</form>
</div>
	<button onclick="$('#addTo').show();$(this).hide();">Add</button>
	<form id="addTo" method="post" action="studentadd.php">
		<fieldset>
			<legend>Add Student</legend>
			<p>
				<label for="addFirst">Firstname</label>
				<input id="addFirst" name="addFirst" type="text">
			</p>
			<p>
				<label for="addLast">Lastname</label>
				<input id="addLast" name="addLast" type="text">
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


<div id="list"></div>

</body>
</html>
