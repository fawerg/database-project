<?php
	ini_set('display_errors','Off');
	session_start();
	include_once('bdlab-lib-fun.php');
	user_logout();
	header ("Location: alter-test.php");
?>