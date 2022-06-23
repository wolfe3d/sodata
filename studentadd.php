<?php
require_once  ("php/functions.php");
userCheckPrivilege(2);

$first = $mysqlConn->real_escape_string($_POST['first']);
$last = $mysqlConn->real_escape_string($_POST['last']);
$yearGraduating = intval($_POST['yearGraduating']);
$schoolID = $_SESSION['userData']['schoolID'];
$studentschoolID = $mysqlConn->real_escape_string($_POST['studentschoolID']);
$scilympiadID = $mysqlConn->real_escape_string($_POST['scilympiadID']);
$email = $mysqlConn->real_escape_string($_POST['email']);
$emailSchool = $mysqlConn->real_escape_string($_POST['emailSchool']);
$phoneType = intval(getIfSet($_POST['phoneType'],1));
$phone = $mysqlConn->real_escape_string($_POST['phone']);
$parent1Last = $mysqlConn->real_escape_string($_POST['parent1Last']);
$parent1First = $mysqlConn->real_escape_string($_POST['parent1First']);
$parent1Phone = $mysqlConn->real_escape_string($_POST['parent1Phone']);
$parent1Email = $mysqlConn->real_escape_string($_POST['parent1Email']);
$parent2Last = $mysqlConn->real_escape_string($_POST['parent2Last']);
$parent2First = $mysqlConn->real_escape_string($_POST['parent2First']);
$parent2Phone = $mysqlConn->real_escape_string($_POST['parent2Phone']);
$parent2Email = $mysqlConn->real_escape_string($_POST['parent2Email']);

//Check to make sure student is not already added that has the same first name, last name, schoolID, and year yearGraduating
$query = "SELECT `studentID` FROM `student` WHERE `last`='$last' AND `first` = '$first' AND `yearGraduating`='$yearGraduating' AND `schoolID` = '$schoolID'";
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
if ($result && mysqli_num_rows($result)>0)
{
	exit("<p>Student already exists with this same name and graduation date.  The duplicate student has not been added.</p><p>Check to make sure the student is not set as inactive.</p><p>If there is two students with the same name and graduation date, add a middle initial to the students' first name or another letter to their first name.</p>");
}
else {
	$query = "INSERT INTO `student` (`first`,`last`,`schoolID`,`studentschoolID`,`scilympiadID`,`yearGraduating`,`email`,`emailSchool`,`phoneType`,`phone`,`parent1Last`,`parent1First`,`parent1Phone`,`parent1Email`,`parent2Last`,`parent2First`,`parent2Phone`,`parent2Email`,`uniqueToken`) VALUES ('$first', '$last', '$schoolID', `studentschoolID`, '$scilympiadID', '$yearGraduating', '$email', '$emailSchool', '$phoneType', '$phone', '$parent1Last', '$parent1First', '$parent1Phone', '$parent1Email', '$parent2Last', '$parent2First', '$parent2Phone', '$parent2Email', 1)";
	$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if ($result)
	{
		echo $mysqlConn->insert_id;//must put this in a variable or echo - before sending to exit;
		exit();
	}
	else {
		exit($query);
	}
}
?>
