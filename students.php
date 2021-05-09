
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

$addStudentText ="";
if($_SESSION['userData']['privilege']>1)
{
	$addStudentText .="<input class='button fa' type='button' onclick='javascript:studentEdit()' value='&#xf067; Add' />";
}
?>
<div>
		<?=$addStudentText?>
		<input class="button fa" type="button" onclick="javascript:toggleSearch()" value="&#xf002; Find" />
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
