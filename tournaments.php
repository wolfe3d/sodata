<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
require_once ("functions.php");
?>
<div>
	<input class="button fa" type="button" onclick="javascript:toggleSearch()" value="&#xf002; Find" > <!-- toggles view of below div -->
	<div id="searchDiv">
	<form id="findTournament">
		<fieldset>
			<legend>Find Tournament</legend>
			<p>
				<label for="tournamentName">Tournament Name</label>
				<input id="tournamentName" name="tournamentName" type="text">
			</p>
			<p>
				<label for="tournamentYear">Tournament Year</label>
				<select name="tournamentYear" id="tournamentYear"><option value="0">All Years</option></select> <span style="color=blue">This is the end of the school year that the tournament took place.  It may be the year after the tournament date.</span>
			</p>
			<p>
				<input class="submit" type="submit" value="Find Tournament">
			</p>
		</fieldset>
	</form>
</div>
<?php if(userHasPrivilege(4))
		{ ?>
	<input class="button fa" type="button" onclick="javascript:toggleAdd()" value="&#xf067; Add" />
	<form id="addTo" method="post" action="tournamentadd.php">
		<fieldset>
			<legend>Add Tournament</legend>
		<?php require_once("tournamentform.php"); ?>
		</fieldset>
		<input class="submit fa" type="submit" value="&#xf067; Add">
	</form>
	</div>
</div>
<?php }?>
<div id="list"></div>
</div>
