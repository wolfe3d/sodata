<?php
require_once  ("../connectsodb.php");
require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
?>
<div>
	<button onclick="$('#searchDiv').show();$(this).hide();">Search</button>
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
<?php if($_SESSION['userData']['privilege']>2)
		{ ?>
	<button onclick="$('#addTo').show();$(this).hide();">Add</button>
	<form id="addTo" method="post" action="studentadd.php">
		<fieldset>
			<legend>Add Tournament</legend>
			<div>TODO: Change all Fields</div>
			//TODO: select previous tournament or add tournament

			<p>
				<label for="first">Firstname</label>
				<input id="first" name="first" type="text">
			</p>
			<p>
				<label for="dateTournament">Tournament Date</label>
				<input id="dateTournament" name="dateTournament" type="date">
			</p>
			<p>
				<label for="dateRegistration">Registration Date</label>
				<input id="dateRegistration" name="dateRegistration" type="date">
			</p>
			<p>
				<label for="year">Tournament Rules Year</label>
				<input id="year" name="year" type="text">
			</p>
			<p>
				<label for="type">Tournament Type</label>
				<select id="type" name="text">
					<?=$phoneTypes?>
				</select>
			</p>
			<p>
				<label for="numberTeams">Number of Teams Registered</label>
				<input id="numberTeams" name="numberTeams" type="text">
			</p>
			<p>
				<label for="weighting">Weighting</label>
				<input id="weighting" name="weighting" type="text">
			</p>
			<p>
				<label for="note">Other Notes</label>
				<input id="note" name="note" type="text">
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
			<p>
				<input class="submit" type="submit" value="Submit">
			</p>
		</fieldset>
	</form>
<?php }?>
<div id="list"></div>
</div>
