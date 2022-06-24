<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);
$output = "<div id='note'></div>";

//Search for slides
$query = "SELECT * FROM `slide` WHERE `schoolID` = ".$_SESSION['userData']['schoolID']." ORDER BY `slideOrder`";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

$output.="<div id='slideList'  style='display: block; margin: auto; max-width:1080px'>";
$slideInt =0;
if ($result && mysqli_num_rows($result) > 0)
{
	while ($row = $result->fetch_assoc()):
		$output.="<div id='slide-".$row['slideID']."'>";
		$output.="<div id='order-".$row['slideID']."'>".$row['slideOrder']."</div> ";
		$output.="<div class=''>";
		$output.="<div id='html-".$row['slideID']."' class='position-absolute w-100 overlayText' style='text-align: center;'>".$row['text']."</div>";
		$output.="<img class='slideImage w-100' id='slide-image-".$row['slideID']."' src='".$row['image']."'>";
		$output.="</div>";
		$output.="<div><input type='file'
       id='image-".$row['slideID']."' name='image-".$row['slideID']."'
       accept='image/png, image/jpeg'>";

		$output.="<button class='btn btn-primary' type='button' onclick='javascript:slideUploadImage(".$row['slideID'].")'><span class='bi bi-upload'></span> Upload</button></div>";
		$output.="<div><!-- Button trigger modal -->
			 <button type='button' class='btn btn-primary' onClick='editSlideText(".$row['slideID'].")'>
			   <span class='bi bi-pencil-square'></span> Edit
			 </button></div>";
			 $output.="<div><!-- Button trigger modal -->
					<button type='button' class='btn btn-primary' onClick='saveSlideText(".$row['slideID'].")'>
						<span class='bi bi-hdd'></span> Save
					</button></div>";
		$output.="<div id='text-".$row['slideID']."'></div>";
		//TODO: Remove button -  will need to reset order.  Maybe, add button to move to first position
		$output.="</div>";
		$slideInt =$row['slideOrder']+1;
	//print each file, with edit button

	//print each text, with edit button


	//Or have a preview show with a button to edit text that causes modal to show up, and edit picture

	endwhile;
}
$output .="</div>";
//Button to Add Slide

echo $output;
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.js"></script>

<button id="edit" class="btn btn-primary" onclick="edit()" type="button">Edit 1</button>
<button id="save" class="btn btn-primary" onclick="save()" type="button">Save 2</button>
<div class="click2edit">click2edit</div>
<br>
<form id="addTo" method="post" action="slideinsert.php"><p>
	<input type="hidden" id="slideOrder" name="slideOrder" value="<?=$slideInt?>">
	<button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button>
	<button class='btn btn-primary' type='button' onclick='javascript:slideAdd(<?=$slideInt?>)'><span class='bi bi-plus-circle'></span> Add New Slide</button>
</p>
</form>
