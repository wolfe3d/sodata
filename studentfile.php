<?php
//header("Content-Type: text/plain");
// Set the content type
header('Content-type: application/csv');
// Set the file name option to a filename of your choice.
header('Content-Disposition: attachment; filename=students.csv');
// Set the encoding
header("Content-Transfer-Encoding: UTF-8");
require_once ("../connectsodb.php");
require_once  ("php/checksession.php"); //Check to make sure user is logged in and has privileges
userCheckPrivilege(4);

$query = "SELECT * FROM `student` WHERE `active` = 1";
$emails = "";

$fp = fopen('php://output', 'a');
$fieldsarr = [];
if ($result = $mysqlConn->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] ."."))
{
  /* Get field information for all columns */
  while ($finfo = $result->fetch_field()):
			array_push($fieldsarr, $finfo->name);
      //printf("Name:     %s\n", $finfo->name);
      //printf("Table:    %s\n", $finfo->table);
      //printf("max. Len: %d\n", $finfo->max_length);
      //printf("Flags:    %d\n", $finfo->flags);
      //printf("Type:     %d\n\n", $finfo->type);
	endwhile;
	fputcsv($fp,$fieldsarr);
	//$fields = implode (",", $fieldsarr);
	//echo $fields;
	while ($row = $result->fetch_row()):
		//$rowtext =implode (",", $row);
		fputcsv($fp,$row);
		 //echo $rowtext;
	endwhile;

	$result->close();
}
fclose($fp);
?>
