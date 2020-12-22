<?php 
	session_start();
	
	require_once "../lib/util.php";

	killSession();
	
	$goBackURL = 'https://'.$_SERVER['HTTP_HOST'];
	if($_SERVER['HTTPS'] !== null) {
		$goBackURL = 'http://'.$_SERVER['HTTP_HOST'];
	}
	header('Location:'.$goBackURL.'/index.php');
	exit;
