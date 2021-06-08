<?php
    require_once ("../connectsodb.php");
    require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
    userCheckPrivilege(1);
    require_once  ("functions.php");

    function getIfSet(&$value, $default = NULL)
    {
    return isset($value) ? $value : $default;
    }

    $name =  $_REQUEST['tournamentName'];
    $host =  $_REQUEST['host'];
    $dateTournament =  $_REQUEST['dateTournament'];
    $dateRegistration =  $_REQUEST['dateRegistration'];
    
    if($_REQUEST['year']){
        $year = intval($_REQUEST['year']);
    }
    else{
        $year = 2025;
    }

    $type =  getIfSet(intval($_REQUEST['type']));
    $numberTeams =  getIfSet(intval($_REQUEST['numberTeams']));
    $weighting =  getIfSet(intval($_REQUEST['weighting']));
    $note =  getIfSet($_REQUEST['note']);
    $address =  getIfSet($_REQUEST['address']);
    $addressBilling =  getIfSet($_REQUEST['addressBilling']);
    $websiteHost =  getIfSet($_REQUEST['websiteHost']);
    $websiteScilympiad =  getIfSet($_REQUEST['websiteScilympiad']);
    $director =  getIfSet($_REQUEST['director']);
    $directorEmail =  getIfSet($_REQUEST['directorEmail']);
    if($_REQUEST['directorPhone']){
        $directorPhone = $_REQUEST['directorPhone'];
    }
    else{
        $directorPhone = '';
    }

    $query = "INSERT INTO `tournament` (`tournamentName`,`host`,`dateTournament`,`dateRegistration`,`year`,`type`,`numberTeams`,`weighting`,`note`,`address`,`addressBilling`,`websiteHost`, `websiteScilympiad`, `director`, `directorEmail`, `directorPhone`) VALUES ('$name', '$host', '$dateTournament', '$dateRegistration', '$year', '$type', '$numberTeams', '$weighting', '$note', '$address', '$addressBilling', '$websiteHost', '$websiteScilympiad', '$director', '$directorEmail', '$directorPhone')";
    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
    if ($result)
	{
		$tournamentID =  $mysqlConn->insert_id;
        echo $tournamentID;

	}
	else {
		exit("Failed to add new tournament.");
        // echo $query;
	}


?>