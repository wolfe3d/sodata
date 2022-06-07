<?php
if(isset($_POST['width']))
{
	$_SESSION['userData']['width']= intval($_POST['width']);
}
echo $_SESSION['userData']['width'];
?>
