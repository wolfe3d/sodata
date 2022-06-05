<?php
//Check to make sure user is logged in and has privileges

/*
User privilege definitions
1 = normal student competitor
2 = event leaders
3 = officer
4 = website developer / db manager /  secretary / captain
5 = coach
All privileges include priviliges below them.
*/
function userCheckPrivilege($level)
{
	if(!isset($_SESSION['userData']) || empty($_SESSION['userData']['privilege']))
	{
		die("<div style='text-danger'>You must be logged in to access this page. <a href='index.php'>Go home.</a></div>");
	}
	if($_SESSION['userData']['privilege']<$level)
	{
		die("<div style='text-danger'>You must have the correct privilige to access this file. <a href='index.php'>Go home.</a></div>");
	}
	if(!$_SESSION['userData']['active'])
	{
		die("<div class='text-danger'>You have been inactivated.  You have either graduated or been removed from the team.  If this is in error, please contact your coach. <a href='index.php'>Go home.</a></div>");
	}
	//TODO: figure out how to refresh Google token here https://stackoverflow.com/questions/9241213/how-to-refresh-token-with-google-api-client
}
function userHasPrivilege($level)
{
	if(!isset($_SESSION['userData'])||$_SESSION['userData']['privilege']<$level)
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
