<?php
require_once ("php/functions.php");
userCheckPrivilege(4);

$where = "WHERE `school`.`schoolID`=".$_SESSION['userData']['schoolID'];
if(userHasPrivilege(5))
{
	$where='';
}

function printResults($db,$query,$student)
{
	//echo $query ;
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$output ="<div class='container-fluid'>";
	if($result && mysqli_num_rows($result)>0)
	{
		while ($row = $result->fetch_assoc()):
			$output.="<div class='d-flex'>";
			$output.="<div class='p-2'>".$row['userID'] ."</div>";
			$output.="<div class='p-2'>".$row['email'] ."</div>";
			$output.="<div class='p-2'>".$row['privilege'] ."</div>";
			$output.="<div class='p-2'>Email Name: ".$row['last_name'] . ", " . $row['first_name'] ."</div>";
			if($student)
			{
				$output.="<div class='p-2'>Student/Coach Name: " .$row['last'] . ", " . $row['first'] ."</div>";
				$output.="<div class='p-2'>" . $row['schoolName'] ."</div>";
			}
			$output.="<div class='ml-auto p-2'><a class='btn btn-dark btn-sm' role='button' href='impersonate.php?userID=".$row['userID']."'><span class='bi bi-file-earmark-person'></span> Impersonate</a></div>";
			$output.="</div>";
		endwhile;
	}
	$output.="</div>";
	return $output;
}

/*get coaches first*/
$output = "<h3>Coaches</h3>";
$query = "SELECT * from `user` INNER JOIN `coach` ON `user`.`userID`=`coach`.`userID` INNER JOIN `school` ON `coach`.`schoolID`=`school`.`schoolID` $where ORDER BY `user`.`userID`";
$output .= printResults($mysqlConn, $query,0);

/*get students*/
$output .= "<h3>Students</h3>";
$query = "SELECT * from `user` INNER JOIN `student` ON `user`.`userID`=`student`.`userID` INNER JOIN `school` ON `student`.`schoolID`=`school`.`schoolID` $where ORDER BY `user`.`userID`";
$output .= printResults($mysqlConn, $query,1);

//see all non identified users.  This does not limit to just the school of the current user
if(userHasPrivilege(5))
{
	$output .= "<h3>Unlinked accounts</h3>";
	$query = "SELECT * FROM `user` D WHERE NOT EXISTS(SELECT * FROM `student` S WHERE D.`userID` = S.`userID`) AND NOT EXISTS (SELECT * FROM `coach` C WHERE D.`userID` = C.`userID`) ORDER BY D.`userID`";
	$output .= printResults($mysqlConn, $query,0);
}

echo $output;
?>
