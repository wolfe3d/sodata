<?php
require_once ("php/functions.php");
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
?>
<div>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleSearch()"><span class='bi bi-search'></span> Find</button> <!-- toggles view of below div -->

	<?php if(userHasPrivilege(3))
	{ ?>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleAdd()"><span class='bi bi-plus'></span> Add</button>

	<form id="addTo" method="post" action="studentadd.php">
		<fieldset>
			<legend>Add Student</legend>
			<?php require_once("studentform.php"); ?>
		</fieldset>
		<p><button class="btn btn-primary" type="submit"><span class='bi bi-plus'></span> Add</button></p>

	</form>
	<!--Output student emails -->
	<a class="btn btn-secondary" role="button" href="#student-emails-<?=getCurrentSOYear();?>"><span class='bi bi-envelope'></span> Get Emails</a>

	<?php }?>
	<?php if(userHasPrivilege(4))
	{ ?>
	<!--Output parent emails -->
	<a class="btn btn-secondary" role="button" href="#parent-emails-<?=getCurrentSOYear();?>"><span class='bi bi-envelope-heart'></span> Get Parents</a>
	<a class="btn btn-secondary" role="button" href="studentfile.php"><span class='bi bi-archive'></span> Students File</a>
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
				<button class="btn btn-primary" type="submit"><span class='bi bi-earmark-person'></span> Find By Name</button>
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
				<button class="btn btn-primary" type="submit"><span class='bi bi-flag'></span> Find By Event</button>
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
				<button class="btn btn-primary" type="submit"><span class='bi bi-flag'></span> Find By Event</button>
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
				<button class="btn btn-primary" type="submit"><span class='bi bi-book'></span> Find By Course</button>
			</p>
		</fieldset>
		</div>
	</form>
<div id="list"></div>
</div>
