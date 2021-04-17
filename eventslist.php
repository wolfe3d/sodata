<?php
require_once ("../connectsodb.php");
//text output
$output = "";

/*check to see if id exists*/
$query = "SELECT * from `events` ORDER BY `events`.`event` ASC";// where `field` = $fieldId";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['event']."</h2>";

		$query = "SELECT * from `eventsyear` WHERE eventsyear.`event` = '".$row['event']."' ORDER BY `eventsyear`.`year` ASC";// where `field` = $fieldId";
		$resultYear = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

		$yearCollection = "";
		while ($rowYear = $resultYear->fetch_assoc()):
			if($yearCollection){
				$yearCollection.=", ";
			}
			$yearCollection .= $rowYear['year'];
		endwhile;


		$output .="<div>Year: $grade ".$yearCollection."</div>";
    $output .="<div>Type: ".$row['type']."</div>";

	endwhile;
  $output .="</div>";
}



echo $output;
?>
