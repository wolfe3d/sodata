<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
?>
<div>
	<input class="button fa" type="button" onclick="javascript:toggleSearch()" value="&#xf002; Find" />
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
<?php if(userHasPrivilege(3))
		{ ?>
	<input class="button fa" type="button" onclick="javascript:toggleAdd()" value="&#xf067; Add" />
	<div id="addDiv">
	<form id="" method="post" action="tournamentedit.php">
		<fieldset>
			<legend>Add Tournament</legend>
			<!-- <div>TODO: Change all Fields</div> -->
			//TODO: select previous tournament or add tournament

			<p>
				<label for="first">Name</label>
				<input id="name" name="name" type="text">
			</p>
			<p>
				<label for="host">Host</label>
				<input id="host" name="host" type="text">
			</p>
			<p>
				<label for="addr">Address</label>
				<input id="addr" name="addr" type="text">
			</p>
			<p>
				<label for="baddr">Billing Address</label>
				<input id="baddr" name="baddr" type="text">
			</p>
			<p>
				<label for="hsite">Host Website</label>
				<input id="hsite" name="hsite" type="text">
			</p>
			<p>
				<label for="site">Scilympiad Competition Website</label>
				<input id="site" name="site" type="text">
			</p>
			<p>
				<label for="year">Tournament Registration Month</label>
				<input id="month" name="month" type="text">
			</p>
			
			<p>
				<input class="submit" type="submit" value="Submit">
			</p>
		</fieldset>
	</form>
	</div>
</div>
<?php }?>
<div id="list"></div>
</div>
