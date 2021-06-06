<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(1);
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
<?php if(userHasPrivilege(3))
		{ ?>
	<input class="button fa" type="button" onclick="javascript:toggleAdd()" value="&#xf067; Add" />
	<form id="addTo" method="post" action="tournamentadd.php">
		<fieldset>
			<legend>Add Tournament</legend>
			<p>
				<label for="tournamentName">Name</label>
				<input id="tournamentName" name="tournamentName" type="text">
			</p>
			<p>
				<label for="host">Host</label>
				<input id="host" name="host" type="text">
			</p>
			<p>
				<label for="address">Address</label>
				<input id="address" name="address" type="text">
			</p>
			<p>
				<label for="dateTournament">Competition Date</label>
				<input id="dateTournament" name="dateTournament" type="date">
			</p>
			<p>
				<label for="dateRegistration">Registration Date</label>
				<input id="dateRegistration" name="dateRegistration" type="text">
			</p>
			<p>
				<label for="year">Competition Year (National Rules Year)</label>
				<select name="year" id="year" type="number">
					<option value=2021>2021</option>
					<option value=2022>2022</option>
					<option value=2023>2023</option>
					<option value=2024>2024</option>
				</select>
				<!-- <input id="year" name="year" type="number"> -->
			</p>
			<p>
				<!--//TODO: Make this a selection -->
				<label for="type">Type of Competition (Full, Mini, Hybrid, etc.)</label>
				<select name="type" id="type" type="number">
					<option value=1>Full</option>
					<option value=2>Mini</option>
					<option value=3>Hybrid</option>
				</select>
				<!-- <input id="type" name="type" type="text"> -->
			</p>
			<p>
				<label for="numberTeams">Number of Teams Registered</label>
				<input id="numberTeams" name="numberTeams" type="number">
			</p>
			<p>
				<label for="weighting">Weighting</label>
				<input id="weighting" name="weighting" type="number">
			</p>
			<p>
				<label for="websiteHost">Host's Website</label>
				<input id="websiteHost" name="websiteHost" type="text">
			</p>
			<p>
				<label for="websiteScilympiad">Host's Scilympiad Site</label>
				<input id="websiteScilympiad" name="websiteScilympiad" type="text">
			</p>
			<p>
				<label for="note">Note(s)</label>
				<input id="note" name="note" type="text">
			</p>
			<p>
				<label for="director">Director(s)</label>
				<input id="director" name="director" type="text">
			</p>
			<p>
				<label for="directorEmail">Director's Email</label>
				<input id="directorEmail" name="directorEmail" type="text">
			</p>
			<p>
				<label for="directorPhone">Director's Phone</label>
				<input id="directorPhone" name="directorPhone" type="text">
			</p>
			<p>
				<label for="addressBilling">Address Billing</label>
				<input id="addressBilling" name="addressBilling" type="text">
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
