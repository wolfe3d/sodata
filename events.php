<?php
require_once ("../connectsodb.php");
//text output
$output = "";

/*check to see if id exists*/
$query = "SELECT * from `eventsyear` ORDER BY `eventsyear`.`year` ASC";// where `field` = $fieldId";
$result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if($result)
{
	$output .="<div>";
	while ($row = $result->fetch_assoc()):
		$output .="<hr><h2>".$row['event']."</h2>";
		$output .="<div>Year: $grade ".$row['year']."</div>";
	endwhile;
  $output .="</div>";
}
echo $output;
?>
