<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);
$output = "<div id='note'></div>";
$schoolID = $_SESSION['userData']['schoolID'];
//Search for slides
$query = "SELECT * FROM `news` WHERE `schoolID` = $schoolID";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$newsID = -1;
$output.="<div id='news'  style='display: block; margin: auto; max-width:1080px'>";
if ($result && mysqli_num_rows($result) > 0)
{
	while ($row = $result->fetch_assoc()):
		$output.=$row['news'];
		$newsID = $row['newsID'];

	endwhile;
}
$output .="</div>";

if(!$newsID)
{
	$query = "INSERT INTO `news` (`schoolID`) VALUES ($schoolID);";
	$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if ($result)
	{
		$newsID = $mysqlConn->insert_id;//must put this in a variable or echo - before sending to exit;
	}
}

if(!$newsID)
{
	exit("ERROR: Adding row to news for new school ($schoolID).");
}
else
{
$output.="<p>
	 <button type='button' id='editButton' class='btn btn-primary' onClick='editText()'>
		 <span class='bi bi-pencil-square'></span> Edit Text
	 </button>";
	 $output.="<div><!-- Button trigger modal -->
			<button type='button' id='saveButton' class='btn btn-primary' onClick='saveText($newsID)'  style='display: none;>
				<span class='bi bi-hdd'></span> Save News
			</button></div>";
}
//Button to Add Slide

echo $output;
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.js"></script>
<script src="js/news.js"></script>

<br>
<form id="addTo" method="post" action="slideinsert.php"><p>
	<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
</p>
</form>
