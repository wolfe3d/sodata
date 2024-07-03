<?php
header("Content-Type: application/json");
require_once  ("php/functions.php");
userCheckPrivilege(2);
$meetingID = intval($_POST['meetingID']);

function getMeetingAttendance($meetingID)
{
    global $mysqlConn;
    $query = "SELECT * FROM `meetingattendance` 
            INNER JOIN `student` 
            ON `student`.`studentID` = `meetingattendance`.`studentID` 
            INNER JOIN `meeting`
            ON `meeting`.`meetingID` = $meetingID
            WHERE `meetingattendance`.`meetingID` = $meetingID";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    if($result && mysqli_num_rows($result)>0)
    {
        $attendanceData = [];
        while ($row = $result->fetch_assoc())
        {
            $data = [
                "studentID" => $row["studentID"],
                "last" => $row["last"],
                "first" => $row["first"],
                "attendance" => $row["attendance"],
                "engagement" => $row["engagement"],
                "homework" => $row["homework"],
                "meetingDate" => $row["meetingDate"],
                "meetingTimeIn" => $row["meetingTimeIn"],
                "meetingTimeOut" => $row["meetingTimeOut"],
                "meetingDescription" => $row["meetingDescription"],
                "meetingHW" => $row["meetingHW"],
            ];
            array_push($attendanceData, $data);
        }
        return $attendanceData;
    }
    else {
        return 0;
    }
}
echo json_encode(getMeetingAttendance($meetingID));
?>
