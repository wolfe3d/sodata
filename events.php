<?php
require_once ("php/functions.php");
userCheckPrivilege(1);
$schoolID = $_SESSION['userData']['schoolID'];
?>
<div>
<button class="btn btn-secondary" type="button" onclick="javascript:toggleSearch()"><span class='bi bi-search'></span> Find</button> <!-- toggles view of below div -->

	<div id="searchDiv">
	<form id="searchDb">
		<fieldset>
			<legend>Find Event by year</legend>
			<p>
				<label for="year">Year</label>
				<?=getSOYears("",1)?>
			</p>
			<p>
				<?=getDivisionList(1)?>
			</p>
			<p>
				<button id="searchDbBtn" class="btn btn-primary" type="submit"><span class='bi bi-binoculars'></span> Search</button>
			</p>
		</fieldset>
	</form>
</div>
<?php if(userHasPrivilege(4)){ ?>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleAdd()"><span class='bi bi-plus-circle'></span> Add</button>

	<form id="addTo" method="post" action="eventadd.php">
		<fieldset>
			<legend>Add Event</legend>
			<?php 	require_once  ("eventform.php"); ?>
			<p><button class="btn btn-primary" type="submit"><span class='bi bi-plus-circle'></span> Add</button><p>
		</fieldset>
	</form>
	<a class='btn btn-secondary' role='button' href='#eventyear-edit-<?=getCurrentSOYear();?>'><span class='bi bi-pencil-square'></span> Edit Year</a>
	<a class='btn btn-secondary' role='button' href='#events-analysis-<?=getCurrentSOYear();?>'><span class='bi bi-pie-chart'></span> Analysis</a>
	
<?php 
	} 
	if(userHasPrivilege(2)) {
?>
	<a class='btn btn-secondary' role='button' href='#event-attendance-<?=getCurrentSOYear();?>'><span class='bi bi-people-fill'></span> Attendance</a>
<?php } ?>

<div id="list"></div>
</div>
