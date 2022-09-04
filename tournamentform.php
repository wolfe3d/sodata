<p>
	<label for="tournamentName">Name</label>
	<input id="tournamentName" name="tournamentName" class="form-control" type="text" value="<?=$row['tournamentName']?>" required>
</p>
<p>
	<input id="notCompetition" name="notCompetition" class="form-check-input" type="checkbox" <?=$row['notCompetition']==1?"checked":""?>>
	<label for="notCompetition" title="If you are using this to create team assignments, check this box.  The most recent notCompetition will show up on the student home page.  There will be no places for this type.">Not a Competition</label>
</p>
<p>
	<label for="host">Host</label>
	<input id="host" name="host" class="form-control" type="text" value="<?=$row['host']?>" required>
</p>
<p>
	<label for="address">Address</label>
	<input id="address" name="address" class="form-control" type="text" value="<?=$row['address']?>">
</p>
<p>
	<label for="dateTournament">Competition Date</label>
	<input id="dateTournament" name="dateTournament" class="form-control" type="date" value="<?=$row['dateTournament']?>" required>
</p>
<p>
	<label for="dateRegistration">Registration Date</label>
	<input id="dateRegistration" name="dateRegistration" class="form-control" type="date" value="<?=$row['dateRegistration']?>" required>
</p>
<p>
	<label for="year">Competition Year (National Rules Year)</label>
	<?=getSOYears($row['year']?$row['year']:getCurrentSOYear())?>
</p>
<p>
	<label for="type">Type of Competition</label>
	<select name="type" id="type" class="form-control" type="number">
		<option value=1>Full</option>
		<option value=2>Mini</option>
		<option value=3>Hybrid</option>
	</select>
</p>
<p>
	<label for="numberTeams">Number of Teams</label>
	<input id="numberTeams" name="numberTeams" class="form-control" type="number" min='0' max='10' value="<?=$row['numberTeams']?>" required>
</p>
<p>
	<label for="weight">Weight</label>
	<input id="weight" name="weight" class="form-control" type="number" min='0' max='100' value="<?=$row['weight']?>">
</p>
<p>
	<label for="teamsAttended">Number of Teams from All Schools Registered</label>
	<input id="teamsAttended" name="teamsAttended" class="form-control" type="number" min='0' value="<?=$row['teamsAttended']?>">
</p>
<p>
	<label for="note">Note(s)</label>
	<input id="note" name="note" class="form-control" type="text" value="<?=$row['note']?>">
</p>
<p>
	<label for="websiteHost">Host's Website</label>
	<input id="websiteHost" name="websiteHost" class="form-control" type="url" value="<?=$row['websiteHost']?>">
</p>
<p>
	<label for="websiteScilympiad">Host's Scilympiad Site</label>
	<input id="websiteScilympiad" name="websiteScilympiad" class="form-control" type="url" value="<?=$row['websiteScilympiad']?>">
</p>
<p>
	<label for="director">Director(s)</label>
	<input id="director" name="director" class="form-control" type="text" value="<?=$row['director']?>">
</p>
<p>
	<label for="directorEmail">Director's Email</label>
	<input id="directorEmail" name="directorEmail" class="form-control" type="email" value="<?=$row['directorEmail']?>">
</p>
<p>
	<label for="directorPhone">Director's Phone (Format: 555-555-5555)</label><?php //https://www.html5pattern.com/Phones ?>
	<input id="directorPhone" name="directorPhone" class="form-control" placeholder="555-555-5555" type="tel" pattern="^\d{3}-\d{3}-\d{4}$" value="<?=$row['addressBilling']?>">
</p>
<p>
	<label for="addressBilling">Address Billing</label>
	<input id="addressBilling" name="addressBilling" class="form-control" type="text" value="<?=$row['addressBilling']?>">
</p>
