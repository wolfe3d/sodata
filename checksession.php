<?php
//Check to make sure user is logged in and has privileges
if(!$_SESSION['userData'] && isset($_SESSION['userData']['privilege']))
{
	die("You must be logged in. <a href='index.html'>Go home.</a>");
}
?>
