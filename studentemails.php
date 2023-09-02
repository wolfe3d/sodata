<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");

userCheckPrivilege(1);
$schoolID = $_SESSION['userData']['schoolID'];
$eventID = intval($_POST['myID']);
$year = getCurrentSOYear();
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$studentIDWhere = "";

if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}
$query = "SELECT `student`.`userID`,`student`.`email`,`student`.`emailSchool`,`student`.`last`, `student`.`first`
FROM `student`
WHERE `student`.`active` AND `student`.`userID` != 9
ORDER BY `student`.`last`, `student`.`first`";
$result = $mysqlConn->query($query);
$emails[] = NULL;
$schoolEmails[] = NULL;

// Header
$output="<h2>Student Emails $year</h2>";
// Create a table for student emails
$output .= "<table id='emails' class='table table-hover table-striped'>";
$output .= "<thead class='table-dark'><tr><th>Name</th><th>Email</th><th>School Email</th></tr></thead><tbody>";

while ($row = $result->fetch_assoc()) {
    $output .= "<tr>";
    $output .= "<td>" . $row["first"] . " " . $row["last"] . "</td>";
    $output .= "<td><a href='mailto: ".$row['email']."'>".$row['email']."</a></td>";
    $output .= "<td><a href='mailto: ".$row['emailSchool']."'>".$row['emailSchool']."</a></td>";
    $output .= "</tr>";

    $emails[] = $row['email'];
	$schoolEmails[] = $row['emailSchool'];
}
if(userHasPrivilege(2))
{
    $output.="<tr>";
	$output.="<td>Total</td>";
	$emailList = implode(';', $emails);
	$schoolEmailList= implode(';', $schoolEmails);
    // Replace links with buttons to copy to clipboard

	// $output.="<td><a href='mailto:$emailList'>Personal Emails</a>, <a href='mailto:$schoolEmailList'>School Emails</a>, <a href='mailto:$emailList;$schoolEmailList'>All Emails</a></td>";
	// $output.="</tr>";
}

echo $output;
?>

    <td><p><button class='btn btn-primary' onclick="copyToClipboard('<?php echo $emailList ?>')" type='button'><span class="bi bi-clipboard-plus"></span> Copy student emails</button></p></td>
    <td><p><button class='btn btn-primary' onclick="copyToClipboard('<?php echo $schoolEmailList ?>')" type='button'><span class="bi bi-clipboard-plus"></span> Copy school emails</button></p></td>
</tr>
</tbody></table>

<script>
    function copyToClipboard(text) {
        var input = document.createElement('textarea');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        alert("Copied to clipboard!");
    }      
</script>

<p><button class='btn btn-outline-secondary' onclick='window.history.back()' type='button'><span class='bi bi-arrow-left-circle'></span> Return</button></p>
