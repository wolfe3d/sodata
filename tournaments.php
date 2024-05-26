<?php
require_once ("php/functions.php");
userCheckPrivilege(1);
$schoolID = $_SESSION['userData']['schoolID'];
$row = null;
?>
<div>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleSearch()"><span class='bi bi-search'></span> Find</button> <!-- toggles view of below div -->
	<div id="searchDiv">
	<form id="searchDb">
		<fieldset>
			<legend>Find Tournament</legend>
			<p>
				<label for="tournamentSearch">Find</label>
				<input id="tournamentSearch" name="tournamentSearch" type="text">
			</p>
			<p>
				<label for="year">Tournament Year</label>
				<?=getSOYears("",1)?>
					 <span style="color:blue">This is the end of the school year that the tournament took place.  It may be the year after the tournament date.</span>
			</p>
			<p>
				<button id="searchDbBtn" class="btn btn-primary" type="submit"><span class='bi bi-controller'></span> Search</button>
			</p>
		</fieldset>
	</form>
</div>
<?php if(userHasPrivilege(4))
		{ ?>
		<button class="btn btn-secondary" type="button" onclick="javascript:toggleAdd()"><span class='bi bi-plus-circle'></span> Add</button>
	<form id="addTo" method="post" action="javascript:alert( 'success!' );">
		<fieldset>
			<legend>Add Tournament</legend>
		<?php require_once("tournamentform.php"); ?>
		</fieldset>
		<p><button class="btn btn-primary" type="submit"><span class='bi bi-plus-circle'></span> Add</button></p>

	</form>
	<a class="btn btn-secondary" role="button" href="#tournaments-score-<?=getCurrentSOYear();?>"><span class='bi bi-pie-chart-fill'></span> Analysis</a>

	</div>
</div>
<?php }?>
<div id="list"></div>
</div>
