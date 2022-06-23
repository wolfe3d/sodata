<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

$slideID = intval($_POST['slideID']);
if(!empty($_FILES["file"]["name"])) {
		// Get file info
		$fileName = basename($_FILES["file"]["name"]);
		$fileType = pathinfo($fileName, PATHINFO_EXTENSION);

		// Allow certain file formats
		$allowTypes = array('jpg','png','jpeg','gif');
		if(in_array($fileType, $allowTypes)){
// This is the file we're going to add it in the database
$image  = $_FILES['file']['tmp_name'];
$imageBase64 = base64_encode(file_get_contents($image ));
$imgContent = 'data:image/'.$fileType.';base64,'.$imageBase64;

		$query = "UPDATE `slide` SET `slide`.`image`='".$imgContent."'
		WHERE `slideID`=$slideID";
		$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if ($result)
		{
			exit($imgContent);
		}
		else
		{
			exit("Unspecified error. Check database log.");
		}
	}
	else {
		exit("Wrong file type sent.");
	}

}
else
{
	//no event id was sent, so initiate adding an event
	exit( "<div style='color:red'>No image sent.</div>");
}
?>
