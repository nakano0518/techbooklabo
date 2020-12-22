<?php
	session_start();
    	
    //テストログイン用ユーザー
	$userId = "testUser1";
	
	$_SESSION['userId'] = $userId;
    	
	$goBackURL = 'https://'.$_SERVER['HTTP_HOST'];
	if($_SERVER['HTTPS'] !== null) {
		$goBackURL = 'http://'.$_SERVER['HTTP_HOST'];	
	}
	header('Location:'.$goBackURL.'/index.php');
	exit;
