<?php
//get all students in a select
function getAllStudents($db, $active, $studentID)
{
	$myOutput = "";
	$where = $active==1?"WHERE `student`.`active` = 1":"";
	$query = "SELECT * from `student` $where ORDER BY `last`,`first` ASC";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result)
	{
		$myOutput .="<select id='studentID' name='studentID' type='text'>";
		$myOutput.="<option value = '0'>None</option>";
		while ($row = $result->fetch_assoc()):
			$selected = $row['studentID']==$studentID ? " selected " : "";
			$myOutput.="<option value = '".$row['studentID']."'$selected>".$row['last'].", ".$row['first']."</option>";
		endwhile;
		$myOutput .="</select>";
	}
	return $myOutput;
}

//get student ID of user
function getStudentID($db, $userID)
{
	$query = "SELECT * from `student` where `userID` = $userID";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['studentID'];
	}
	return 0;
}

//get Event type options
/*function getEventTypes($db, $type)
{
	$myOutput = "";
	$query = "SELECT * from `eventtype`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result)
	{
		$myOutput .="<select id='type' name='type' type='text'>";
		while ($row = $result->fetch_assoc()):
			$selected = $row['type']==$type ? " selected " : "";
			$myOutput.="<option value = '".$row['type']."'$selected>".$row['type']."</option>";
		endwhile;
		$myOutput .="</select>";
	}
	return $myOutput;
}*/
//get Event type options
function getEventString($type)
{
	if (!$type || $type == 0){
    return "Core Knowledge (Test Only)";
	}
	elseif ($type == 1) {
	    return "Build";
	}
	elseif ($type == 2) {
			return "Laboratory or Hands On";
	}
	elseif ($type == 3) {
	    return "Hybrid Build";
	}
	elseif ($type == 4) {
	    return "Hybrid Lab";
	}
}

//Return Calculator Type
function getCalulatorString($type)
{
	if (!$type || $type == 0){
    return "None";
	}
	elseif ($type == 1) {
	    return "Graphing or any other type";
	}
	elseif ($type == 2) {
	    return "4-Function Only";
	}
	elseif ($type == 3) {
	    return "Scientific or 4-Function Only";
	}
}

//return Goggle Type
function getGoggleString($type)
{
	if (!$type || $type == 0){
    return "None";
	}
	elseif ($type == 1) {
	    return "Class B - Impact only, for Most Builds";
	}
	elseif ($type == 2) {
	    return "Class C - Splash Resistant, for Most Labs";
	}
}

//get list of events
function getEventList($db, $number,$label)
{
	$query = "SELECT * FROM `event` ORDER BY `event` ASC";
	$resultEventsList = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	$events ="<div id='eventsListDiv'><label for='eventsList'>$label</label> ";
	$events .="<select id='eventsList-$number' name='eventsList'>";
		if($resultEventsList)
		{
			while ($rowEvents = $resultEventsList->fetch_assoc()):
				$event = htmlspecialchars($db->real_escape_string($rowEvents['event']));
				$type = getEventString($rowEvents['type']);
				$events .= "<option value='".$rowEvents['eventID']."'>$event - $type</option>";
			endwhile;
		}
		$events.="</select></div>";
	return $events;
}

//get Teams from previous tournaments
function getTeamsPrevious($db)
{
	$myOutput = "";
	$query = "SELECT * from `team` INNER JOIN `tournament` ON `team`.`tournamentID`=`tournament`.`tournamentID`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

	if($result)
	{
		$myOutput .="<select id='teamTournament' name='teamTournament' type='text'>";
		while ($row = $result->fetch_assoc()):
			$myOutput.="<option value = '". $row['teamID'] ."'>".$row['tournamentName']." " . $row['teamName'] ."</option>";
		endwhile;
		$myOutput .="</select>";
	}
	return $myOutput;
}
//Get all Science Olympiad years from 1982 to current year+1
function getSOYears($myYear)
{
	$output = "";
	$output .= "<select id='year' name='year'>";
	$i = getCurrentSOYear() + 1;
	for ($i ; $i >= 1982; $i--) {
				$selected = $myYear==$i ? "selected" : "";
				$output .="<option value='$i' $selected>$i</option>";
	}
	$output .="</select>";
	return $output;
}
//get Current Science Olympiad year
function getCurrentSOYear()
{
	if (date("m")>4)
	{
		return date("Y")+1;
	}
	return date("Y");
}
//get Current Officer Position
function getOfficerPosition($db,$studentID)
{
	$year = date("m")>4 ? date("Y")+1 : date("Y");
	$query = "SELECT * FROM `officer` WHERE `studentID`=$studentID AND `year`=$year";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		$row = $result->fetch_assoc();
		return $row['position'];
	}
	return "";
}

//get Previous Officer Position List
function getPreviousOfficerPosition($db,$studentID)
{
	$myOutput = "";
	$year = date("m")>4 ? date("Y")+1 : date("Y");
	$query = "SELECT * FROM `officer` WHERE `studentID`=$studentID AND `year`< $year ORDER BY `year` DESC";
	$result = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		while ($row = $result->fetch_assoc()):
			$myOutput .= $myOutput ? ", ":"";
			$myOutput .= $row['year']."-".$row['position'];
		endwhile;
		return $myOutput;
	}
	return "";
}

//get phone types student's course enrolled/completed
function getPhoneTypes($db)
{
	$myOutput = "";
	$query = "SELECT * from `phonetype`";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if($result)
	{
		while ($row = $result->fetch_assoc()):
			//echo $row['phoneType'];
			$myOutput.="<option value = '".$row['phoneType']."'>".$row['phoneType']."</option>";
		endwhile;
	}
	return $myOutput;
}

//find student's course enrolled/completed
function getCourses($db, $studentID, $tableName)
{
	$myOutput = "";
	$tableID = $tableName . "ID";
	$query = "SELECT * FROM `$tableName` t1 INNER JOIN `course` t2 ON t1.`courseID`=t2.`courseID` WHERE `studentID`=$studentID ORDER BY t2.`course` ASC";// where `field` = $fieldId";
	$result = $db->query($query) or error_log("\n<br />Warning: query failed:$query. " . $db->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
	if(mysqli_num_rows($result)>0)
	{
		$myOutput .="<div>Course Name - Level</div>";
		while ($row = $result->fetch_assoc()):
			if($tableName == "courseenrolled")
			{
				$courseCompleted = "<a href=\"javascript:studentCourseCompleted('" . $row[$tableID] . "','" . $row['course'] . "')\">Completed</a>";
			}
			$myOutput .= "<div id='$tableName-" . $row[$tableID] . "'><span class='course'>" . $row['course'] . " - " . $row['level'] . " $courseCompleted  </span><a href=\"javascript:studentCourseRemove('" . $row[$tableID] . "','$tableName')\">Remove</a></div>";
		endwhile;
	}
	return $myOutput;
}

//get tournament
function getTournamentDates()
{

}

//print out privilege editing
function editPrivilege($privilege,$userID,$db)
{
	$output = "";
	if($_SESSION['userData']['privilege']>$privilege-1) //student must have the current privilege or higher
	{
		$output .= "<fieldset><legend>Privilege</legend><p>";
		//make an adjustable privilege container for website manager to give higher privileges
		//show privilege
		if(empty($userID))
		{
				$output .= "User has never logged in with registered account.";
		}
		else {
			$query = "SELECT * FROM `user` WHERE `id`=".$userID;// where `field` = $fieldId";
			$resultPrivilege = $db->query($query) or print("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
			$rowPriv = $resultPrivilege->fetch_assoc();
			if ($rowPriv['privilege'])
			{
					$output .= "<label for='privilege'>Privilege</label>";
					$output .= "<input id='privilege' name='privilege' type='text' value='".$rowPriv['privilege']."' onchange='userPrivilege(".$userID.",this.id,this.value)'>";
			}
			else
			{
					$output .=  "User has never logged in with registered account.";
			}
		}
		$output .=  "</p></fieldset>";
	}
	return $output;
}
//Check to make sure google is logged in and set variables
function checkGoogle($gpUserProfile,$db)
{
	// Include User library file
	require_once 'User.class.php';

  // Initialize User class
  $user = new User($db);

  // Getting user profile info
  $gpUserData = array();
  $gpUserData['oauth_uid']  = !empty($gpUserProfile['id'])?$gpUserProfile['id']:'';
  $gpUserData['first_name'] = !empty($gpUserProfile['given_name'])?$gpUserProfile['given_name']:'';
  $gpUserData['last_name']  = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:'';
  $gpUserData['email'] = !empty($gpUserProfile['email'])?$gpUserProfile['email']:'';
  $gpUserData['gender'] = !empty($gpUserProfile['gender'])?$gpUserProfile['gender']:'';
  $gpUserData['locale'] = !empty($gpUserProfile['locale'])?$gpUserProfile['locale']:'';
  $gpUserData['picture'] = !empty($gpUserProfile['picture'])?$gpUserProfile['picture']:'';

  // Insert or update user data to the database
  $gpUserData['oauth_provider'] = 'google';
  $userData = $user->checkUser($gpUserData);
  // Storing user data in the session
  $_SESSION['userData'] = $userData;
}

//convert rgb color to hexadecimal
//TODO: possible remove this function.  Currently, deactivated but check all pages to make sure no effect.
/*function rgb($rgb) {
    $ret = '';
    foreach ($rgb as $x) {
        // Make sure the RGB values are 0-255...
        $x = max(0, min(255, $x));
        // create a 2 digit hex value for this color component...
        $ret .= ($x < 16 ? '0'.dechex($x) : dechex($x));
    }
    return '#'.$ret;
}*/

// Returns a color that is part of the rainbow -- not in order of ROYGBIV
function rainbow($i) {
		$opacity = 0.2;
    $rgb = array(255,255,0); //yellow
    // Go through the RGB values and adjust the values by $amount...
		switch($i) {
			case 1:
				$rgb = array(255,128,0); //orange
				break;
			case 2:
				$rgb = array(255,0,0); //red
				break;
			case 3:
				$rgb = array(255,0,128); //Rose
				break;
			case 4:
				$rgb = array(255,0,255); //Magenta
				break;
			case 5:
				$rgb = array(128,0,255); //violet
				break;
			case 6:
				$rgb = array(0,0,255); //blue
				break;
			case 7:
				$rgb = array(0,128,255); //Azure
				break;
			case 8:
				$rgb = array(0,255,255); //cyan
				break;
			case 9:
				$rgb = array(0,255,128); //Spring Green
				break;
			case 10:
				$rgb = array(0,255,0); //Green
				break;
			case 11:
				$rgb = array(128,255,0); //Chartreuse
				break;
		  default:
		    // code block
		}
    return "rgba(".implode($rgb,",").", $opacity);";
}

/**
 * Generate a random string, using a cryptographically secure
 * pseudorandom number generator (random_int)
 *
 * This function uses type hints now (PHP 7+ only), but it was originally
 * written for PHP 5 as well.
 *
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 *
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}
//usage
/*
$a = random_str(32);
$b = random_str(8, 'abcdefghijklmnopqrstuvwxyz');
$c = random_str();
*/

/*
get a token is not used elsewhere in the table
*/
function get_uniqueToken($db, $tableName)
{
	$uniqueToken = random_str(20);
	$query ="SELECT * FROM `$tableName` WHERE `uniqueToken` LIKE '$uniqueToken'";
	echo $query;
	$result = $db->query($query);
	if ($row = $result->fetch_row()) {
    return get_uniqueToken($db,$tableName);
	} else {
    return $uniqueToken;
	}
}
?>
