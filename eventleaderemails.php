<?php
require_once  ("php/functions.php");

userCheckPrivilege(3);
$eventID = intval($_POST['myID']);
$year = getCurrentSOYear();
$studentID = getStudentID($_SESSION['userData']['userID']);
$studentIDWhere = "";

if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}
$query = "SELECT DISTINCT `first`, `last`, `email`, `emailSchool`, `event`.`event`
FROM `eventleader` 
INNER JOIN `student` ON `eventleader`.`studentID`= `student`.`studentID` 
INNER JOIN `event` ON `eventleader`.`eventID`=`event`.`eventID`
WHERE `student`.`active` AND `student`.`userID` != 9 AND `student`.`schoolID` = $schoolID AND `year`=$year
ORDER BY `student`.`last`, `student`.`first`";
$result = $mysqlConn->query($query);
$emails[] = NULL;
$schoolEmails[] = NULL;

// Header
$output="<h2>Event Leader Emails $year</h2>";
// Create a table for student emails
$output .= "<table id='emails' class='table table-hover table-striped'>";
$output .= "<thead class='table-dark'><tr><th>Name</th><th>Event</th><th>Email</th><th>School Email</th></tr></thead><tbody>";

while ($row = $result->fetch_assoc()) {
    $output .= "<tr>";
    $output .= "<td>" . $row["first"] . " " . $row["last"] . "</td>";
    $output .= "<td>" . $row["event"] . "</td>";
    $output .= "<td><a href='mailto:".$row['email']."'>".$row['email']."</a></td>";
    $output .= "<td><a href='mailto:".$row['emailSchool']."'>".$row['emailSchool']."</a></td>";
    $output .= "</tr>";

    $emails[] = $row['email'];
	$schoolEmails[] = $row['emailSchool'];
}
if(userHasPrivilege(3))
{
    $output.="<tr>";
	$output.="<td>Total</td>";
	$emailList = implode(';', array_filter($emails)); //array_filter removes null values
	$schoolEmailList= implode(';', array_filter($schoolEmails)); //array_filter removes null values
}
$output .= "<td></td>";
$output .= "<td><p><button class='btn btn-primary' onclick='copyToClipboard(\"" . $emailList . "\")' type='button'><span class='bi bi-clipboard-plus'></span> Copy student emails</button></p></td>";
$output .= "<td><p><button class='btn btn-primary' onclick='copyToClipboard(\"" . $schoolEmailList . "\")' type='button'><span class='bi bi-clipboard-plus'></span> Copy school emails</button></p></td>";
$output .= "</tr></tbody></table>";
echo $output;
?>


<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
