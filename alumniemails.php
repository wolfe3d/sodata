<?php
header("Content-Type: text/plain");
require_once  ("php/functions.php");

userCheckPrivilege(3);
$schoolID = $_SESSION['userData']['schoolID'];
$year = getCurrentSOYear();
$studentID = getStudentID($mysqlConn, $_SESSION['userData']['userID']);
$studentIDWhere = "";
if($studentID)
{
	$studentIDWhere ="AND `student`.`studentID` != $studentID";
}

$query = "SELECT `student`.`studentID`, `student`.`first`, `student`.`last`, `student`.`yearGraduating`, `student`.`schoolID`, `student`.`phoneType`, `student`.`phone`, `student`.`email`
FROM `student`
WHERE `student`.`yearGraduating` < $year AND `student`.`goodStanding` = 1 AND `student`.`schoolID` = $schoolID
ORDER BY `student`.`yearGraduating`, `student`.`last`, `student`.`first`";
$result = $mysqlConn->query($query);
$students = [];
$emails[] = NULL;

// Header
$output="<h2>Alumni Emails $year</h2>";
// Create a table for student emails
$output .= "<table id='emails' class='table table-hover table-striped'>";
$output .= "<thead class='table-dark'><tr><th>Year Graduated</th><th>Name</th><th>Email</th></tr></thead><tbody>";

while ($row = $result->fetch_assoc()) {
    $output .= "<tr>";
    if($row['yearGraduating'])
	{
		$output .="<tr><td>".$row['yearGraduating']."</td>";
	}
    $output .= "<td>" . $row["first"] . " " . $row["last"] . "</td>";
    $output .= "<td><a href='mailto:".$row['email']."'>".$row['email']."</a></td>";
    $output .= "</tr>";

    $emails[] = $row['email'];
    $students[] = $row;
    $output .= "</tr>";
}
if(userHasPrivilege(3))
{
    $output.="<tr>";
	$emailList = implode(';', array_filter($emails)); //array_filter removes null values
}
echo $output;
?>
    <td>Total</td>
    <td></td>
    <td><p><button class='btn btn-primary' onclick="copyToClipboard('<?php echo $emailList ?>')" type='button'><span class="bi bi-clipboard-plus"></span> Copy student emails</button></p></td>
</tr>
</tbody></table>
<p>Note: This list only shows students in good standings that may be interested in contributing once they have graduated.</p>
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

