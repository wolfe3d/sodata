<?php
//Check to make sure user is logged in and has privileges
if(!$_SESSION['userData'] || empty($_SESSION['userData']['privilege']))
{
	die("<div style='color:red'>You must be logged in and have the correct permissions to access this page. <a href='index.html'>Go home.</a></div>");
}
?>
