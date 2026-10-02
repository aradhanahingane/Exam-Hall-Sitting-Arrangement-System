<?php
	session_start();
	unset($_SESSION["username"]);
          	
	if(!array_key_exists("username", $_SESSION))
	{
		clearstatcache();		
		header("location:pagenotfound.php");
	} 
	elseif(empty($_SESSION["username"]))
	{
		clearstatcache();
		header("location:pagenotfound.php");
	}
	clearstatcache();
	session_destroy();
	$_SESSION = array();
    	header("location:start.php");
?>

