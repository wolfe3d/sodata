<?php
require_once  ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once ("php/functions.php");
$row = null;
?>
<div>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleSearch()"><span class='fa'>&#xf002;</span> Find</button> <!-- toggles view of below div -->
	<div id="searchDiv">
	<form id="findTournament">
		<fieldset>
			<legend>Find Tournament</legend>
			<p>
				<label for="tournamentName">Tournament Name</label>
				<input id="tournamentName" name="tournamentName" type="text">
			</p>
			<p>
				<label for="year">Tournament Year</label>
				<?=getSOYears("",1)?>
					 <span style="color:blue">This is the end of the school year that the tournament took place.  It may be the year after the tournament date.</span>
			</p>
			<p>
				<button class="btn btn-primary" type="submit"><span class='fa'>&#xf024;</span> Find Tournament</button>
			</p>
		</fieldset>
	</form>
</div>
<?php if(userHasPrivilege(4))
		{ ?>
		<button class="btn btn-secondary" type="button" onclick="javascript:toggleAdd()"><span class='fa'>&#xf067;</span> Add</button>
	<form id="addTo" method="post" action="tournamentadd.php">
		<fieldset>
			<legend>Add Tournament</legend>
		<?php require_once("tournamentform.php"); ?>
		</fieldset>
		<button class="btn btn-primary" type="submit"><span class='fa'>&#xf067;</span> Add</button>

	</form>
	<a class="btn btn-secondary" role="button" href="#tournaments-score-<?=getCurrentSOYear();?>"><span class='fa'>&#xf200;</span> Analysis</a>

	</div>
</div>
<?php }?>
<div id="list"></div>
</div>
