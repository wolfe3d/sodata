<?php require_once  ("functions.php"); ?>

<p>
	<label for="tournamentName">Name</label>
	<input id="tournamentName" name="tournamentName" type="text" value="<?=$row['tournamentName']?>">
</p>
<p>
	<label for="host">Host</label>
	<input id="host" name="host" type="text" value="<?=$row['host']?>">
</p>
<p>
	<label for="address">Address</label>
	<input id="address" name="address" type="text" value="<?=$row['address']?>">
</p>
<p>
	<label for="dateTournament">Competition Date</label>
	<input id="dateTournament" name="dateTournament" type="date" value="<?=$row['dateTournament']?>">
</p>
<p>
	<label for="dateRegistration">Registration Date</label>
	<input id="dateRegistration" name="dateRegistration" type="date" value="<?=$row['dateRegistration']?>">
</p>
<p>
	<label for="year">Competition Year (National Rules Year)</label>
	<?=getSOYears($row['year']?$row['year']:getCurrentSOYear())?>
</p>
<p>
	<label for="type">Type of Competition</label>
	<select name="type" id="type" type="number">
		<option value=1>Full</option>
		<option value=2>Mini</option>
		<option value=3>Hybrid</option>
	</select>
</p>
<p>
	<label for="numberTeams">Number of Teams Registered</label>
	<input id="numberTeams" name="numberTeams" type="number" value="<?=$row['numberTeams']?>">
</p>
<p>
	<label for="weight">Weight</label>
	<input id="weight" name="weight" type="number" min='0' max='100' value="<?=$row['weight']?>">
</p>
<p>
	<label for="note">Note(s)</label>
	<input id="note" name="note" type="text" value="<?=$row['note']?>">
</p>
<p>
	<label for="websiteHost">Host's Website</label>
	<input id="websiteHost" name="websiteHost" type="text" value="<?=$row['websiteHost']?>">
</p>
<p>
	<label for="websiteScilympiad">Host's Scilympiad Site</label>
	<input id="websiteScilympiad" name="websiteScilympiad" type="text" value="<?=$row['websiteScilympiad']?>">
</p>
<p>
	<label for="director">Director(s)</label>
	<input id="director" name="director" type="text" value="<?=$row['director']?>">
</p>
<p>
	<label for="directorEmail">Director's Email</label>
	<input id="directorEmail" name="directorEmail" type="text" value="<?=$row['directorEmail']?>">
</p>
<p>
	<label for="directorPhone">Director's Phone</label>
	<input id="directorPhone" name="directorPhone" type="text" value="<?=$row['addressBilling']?>">
</p>
<p>
	<label for="addressBilling">Address Billing</label>
	<input id="addressBilling" name="addressBilling" type="text" value="<?=$row['addressBilling']?>">
</p>
