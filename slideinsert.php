<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

$slideNumber = intval($_POST['slideOrder']);
if($schoolID)
	{
		$query = "INSERT INTO `slide` (`schoolID`,`slideOrder`) VALUES ($schoolID,$slideNumber);";
		$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
		if ($result)
		{
			echo $mysqlConn->insert_id;//must put this in a variable or echo - before sending to exit;
			exit();
		}
		else
		{
			exit("Unspecified error. Check database log.");
		}
}
else
{
	//no event id was sent, so initiate adding an event
	exit( "<div style='color:red'>No school identified with this user.</div>");
}

?>
