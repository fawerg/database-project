<?php
	ini_set('display_errors','Off');
	session_start();
	include_once('bdlab-lib-fun.php');
	
	if (user_check($_POST['user'], $_POST['pass'])) {
		header ("Location: alter-test.php");}
	else
		header ("Location: alter-test.php?failed=true");               
?>