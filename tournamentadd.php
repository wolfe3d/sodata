<?php
    require_once ("../connectsodb.php");
    require_once  ("checksession.php"); //Check to make sure user is logged in and has privileges
    userCheckPrivilege(1);
    require_once  ("functions.php");

//TODO:  Chinmay, why is there a function getIfSet?  If the value is not set, it will return a null value by default in php. Unless, you plan to set default values here; however, most default values can be set in db...except see line 18.  Is the function meant to make the db notice the value as null?
    function getIfSet(&$value, $default = NULL)
    {
    	return isset($value) ? $value : $default;
    }

		//TODO: Chinmay, always use Post unless it is necessary to pick up information from get and post.  Using request, allows users to send data via website links to the server.  Using post limits it form posts.
		//TODO: Chinmay,  all strings must be escaped before entering into mysql database. Otherwise, it is super easy for MySQL injection to damage database.
		//therefore, use "$value = $mysqlConn->real_escape_string($_POST['myvalue']);"
    $name =  $_REQUEST['tournamentName'];
    $host =  $_REQUEST['host'];
    $dateTournament =  $_REQUEST['dateTournament'];
    $dateRegistration =  $_REQUEST['dateRegistration'];

		//TODO: Chinmay, the lines below are where your function getIfSet could be used to produce the default value.  There is a function that gives the current year already made in functions.php.
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
