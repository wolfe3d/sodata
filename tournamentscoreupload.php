<?php
require_once  ("php/functions.php");
userCheckPrivilege(4);

$schoolID =$_SESSION['userData']['schoolID'];
$tournamentID = $_POST['tournamentID'];
$query = "SELECT `tournament`.`fileName` from `tournament` WHERE `tournament`.`tournamentID` = $tournamentID AND `tournament`.`schoolID` = $schoolID = " . $_SESSION['userData']['schoolID'];
$result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");

if(empty($result))
{
	echo "Query Tournament Score Upload Failed. ";
	exit("0");
}

$row = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (isset($_FILES["file"]) && isset($_POST["fileid"]) && $_FILES["file"]["error"] == 0)
    {
        $allowed_ext = array("jpg" => "image/jpg",
                            "jpeg" => "image/jpeg",
                            "gif" => "image/gif",
                            "png" => "image/png",
                            "pdf" => "application/pdf",
                            "xls" => "application/vnd.ms-excel",
                            "xlsx" => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $file_name = $_FILES["file"]["name"];
        $file_type = $_FILES["file"]["type"];
        $file_size = $_FILES["file"]["size"];
     
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
 
        if (!array_key_exists($ext, $allowed_ext))        
            die("Error: Please select a valid file format.");
         
        $maxsize = 1024 * 1024;     // max file size is 1mb
 
        if ($file_size > $maxsize)        
            die("Error: File size is larger than 1MB");        
        else {
            // MIME type
            if (in_array($file_type, $allowed_ext))
            {
                if (!file_exists("uploads")) {
                    mkdir("uploads", 0777, true); // create directory with full permissions
                }
                $new_file_name = $_POST[ 'fileid' ].".{$ext}";
                // no duplicate file names
                if (file_exists("uploads/".$new_file_name))     
                {       
                    echo $new_file_name." already exists! ";
                    exit("0");
                }
                else
                {
                    move_uploaded_file($_FILES["file"]["tmp_name"],
                            "uploads/".$new_file_name);
                    //echo($new_file_name);
                    $query = "UPDATE `tournament` SET `fileName` = '{$new_file_name}' WHERE `tournament`.`tournamentID` = $tournamentID";
                    $result = $mysqlConn->query($query) or error_log("\n<br />Warning: query failed:$query. " . $mysqlConn->error. ". At file:". __FILE__ ." by " . $_SERVER['REMOTE_ADDR'] .".");
                    exit("1");
                }
            }
            else
            {
                echo "Error: Please try again. ";
                exit("0");
            }
        }
    }
    else
    {
        echo "Error: ". $_FILES["file"]["error"];
        exit("0");
    }
}
?>