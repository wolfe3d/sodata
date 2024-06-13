<?php
require_once("php/functions.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // variables for meeting table
    $meetingName = htmlspecialchars($_POST['meetingName']);
    $meetingDate = htmlspecialchars($_POST['meetingDate']);
    $meetingType = filter_input(INPUT_POST, "meetingType", FILTER_VALIDATE_INT);
    $eventID = htmlspecialchars($_POST['meetingName']);
    $meetingTimeIn = htmlspecialchars($_POST['meetingTimeIn']);
    $meetingTimeOut = htmlspecialchars($_POST['meetingTimeOut']);
    $meetingDescription = htmlspecialchars($_POST['desc']);
    $meetingHW = htmlspecialchars($_POST['meetingHW']);

    $query = "INSERT INTO `meeting` (`meetingTypeID`, `eventID`, `meetingDate`, `meetingTimeIn`, `meetingTimeOut`, `meetingDescription`, `meetingHW`) 
              VALUES ('$meetingType', '$eventID', '$meetingDate', '$meetingTimeIn', '$meetingTimeOut', '$meetingDescription', '$meetingHW') ";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    $meetingID = $mysqlConn->insert_id;
    // Handle meeting attendance for each student
    $studentData = [];
    foreach ($_POST as $key => $value) {
        $studentID = $attendance = $engagement = $homework = $type = null;
        // Check if the key starts with 'attendance-' (TODO: make this better/less hardcoded)
        if (strpos($key, 'attendance-') === 0) {
            $studentID = substr($key, strlen('attendance-'));
            //attendance value will be 1 for present, 0 for absent excused, -1 for absent unexcused
            $attendance = $value;
        }
        elseif (strpos($key, 'engagement-') === 0) {
            $studentID = substr($key, strlen('engagement-'));
            $engagement = $value;
        }
        elseif (strpos($key, 'homework-') === 0) {
            $studentID = substr($key, strlen('homework-'));
            $homework = $value;
        }
        if ($studentID !== null) {
            if (!isset($studentData[$studentID])) {
                // Initialize student data at their studentID
                $studentData[$studentID] = [
                    'attendance' => '',
                    'engagement' => '',
                    'homework' => ''
                ];
            }
            // Assign values based on the key
            if ($attendance !== null) {
                $studentData[$studentID]['attendance'] = $attendance;
            }
            if ($engagement !== null) {
                $studentData[$studentID]['engagement'] = $engagement;
            }
            if ($homework !== null) {
                $studentData[$studentID]['homework'] = $homework;
            }
        } 
    }
    foreach ($studentData as $studentID => $data) {
        $attendance = $data['attendance'];
        $engagement = $data['engagement'];
        $homework = $data['homework'];

        $query = "INSERT INTO `meetingattendance` (`meetingID`, `studentID`, `attendance`, `engagement`, `homework`) 
                  VALUES ('$meetingID', '$studentID', '$attendance', '$engagement', '$homework') ";
        $result = $mysqlConn->query($query);

        if (!$result) {
            error_log("\n<br />Warning: query failed: $query. " . $mysqlConn->error . ". At file: " . __FILE__ . " by " . $_SERVER['REMOTE_ADDR']);
            return(0);
        }
    }
}
return(1);