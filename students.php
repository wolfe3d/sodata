<?php
require_once ("php/functions.php");
userCheckPrivilege(1);
?>
<div>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleSearch()"><span class='bi bi-search'></span> Find</button> <!-- toggles view of below div -->

	<?php if(userHasPrivilege(3))
	{ ?>
	<button class="btn btn-secondary" type="button" onclick="javascript:toggleAdd()"><span class='bi bi-plus-circle'></span> Add</button>

	<form id="addTo" method="post" action="studentadd.php">
		<fieldset>
			<legend>Add Student</legend>
			<?php require_once("studentform.php"); ?>
		</fieldset>
		<p><button class="btn btn-primary" type="submit"><span class='bi bi-plus-circle'></span> Add</button></p>

	</form>
	<!--Output student emails -->
	<a class="btn btn-secondary" role="button" href="#student-emails-<?=getCurrentSOYear();?>"><span class='bi bi-envelope'></span> Get Emails</a>

	<?php }?>
	<?php if(userHasPrivilege(4))
	{ ?>
	<!--Output parent emails -->
	<a class="btn btn-secondary" role="button" href="#parent-emails-<?=getCurrentSOYear();?>"><span class='bi bi-envelope-heart'></span> Get Parents</a>
	<a class="btn btn-secondary" role="button" href="studentfile.php"><span class='bi bi-archive'></span> Students File</a>
	<a class="btn btn-secondary" role="button" href="awards.php"><span class='bi bi-archive'></span> Awards List</a>
	<?php }?>
<br><br>
	<form id="searchDb">
		<div>
			<input type="checkbox" id="active" name="active" value="1" class="form-check-input" checked>
			<label for="active" class="form-check-label">Show only active students</label>
		</div>
		<div id="searchDiv">
			<p>
				<label for="search">Find</label>
				<input id="search" name="search" class="form-control" type="text">
			</p>
			<p>
				<button id="searchDbBtn" class="btn btn-primary" type="submit"><span class='bi bi-file-earmark-person'></span> Search</button>
			</p>
		</div>
	</form>
<div id="list"></div>
</div>
