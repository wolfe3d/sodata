<?php
require_once ("php/functions.php");
userCheckPrivilege(1);
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
				<?=getDivisionList($mysqlConn,1)?>
			</p>
			<p>
				<button class="btn btn-primary" type="submit"><span class='fa fa-search'></span> Search</button>
			</p>
		</fieldset>
	</form>
</div>
<?php if(userHasPrivilege(4)){ ?>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleAdd()"><span class='fa'>&#xf067;</span> Add</button>

	<form id="addTo" method="post" action="eventadd.php">
		<fieldset>
			<legend>Add Event</legend>
			<?php 	require_once  ("eventform.php"); ?>
			<p><button class="btn btn-primary" type="submit"><span class='fa'>&#xf067;</span> Add</button><p>
		</fieldset>
	</form>
	<a class='btn btn-secondary' role='button' href='#eventyear-edit-<?=getCurrentSOYear();?>'><span class='fa'>&#xf133;</span> Edit Year</a>
	<a class='btn btn-secondary' role='button' href='#events-analysis-<?=getCurrentSOYear();?>'><span class='fa'>&#xf200;</span> Analysis</a>
<?php } ?>

<div id="list"></div>
</div>
