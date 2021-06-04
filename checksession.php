<?php
//Check to make sure user is logged in and has privileges
function userCheckPrivilege($level)
{
	if(!$_SESSION['userData'] || empty($_SESSION['userData']['privilege']))
	{
		die("<div style='color:red'>You must be logged in to access this page. <a href='index.php'>Go home.</a></div>");
	}
	if($_SESSION['userData']['privilege']<$level)
	{
		die("<div style='color:red'>You must have the correct privilige to access this file. <a href='data.php'>Go home.</a></div>");
	}
}
function userHasPrivilege($level)
{
	if(!$_SESSION['userData']||$_SESSION['userData']['privilege']<$level)
	{
		return 0;
	}
	return 1;
}
function userGetPrivilege()
{
	return $_SESSION['userData']['privilege'];
}
?>
