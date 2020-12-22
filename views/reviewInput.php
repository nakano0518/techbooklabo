<?php
		session_start();
    	
    	require_once '../config/envLoad.php';
		require_once "../lib/util.php";
	

    	if(isset($_POST['bookNo']) && !empty($_POST['bookNo'])){
		$bookNo = $_POST['bookNo'];    
    	}
   
    	if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])){
		 $userId = $_SESSION['userId'];
    	}
    
		if(isset($_POST['reviewContent']) && !empty($_POST['reviewContent'])){
        	$reviewContent = $_POST['reviewContent'];
    	}else{
			$goBackURL = "https://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']);
        	if($_SERVER['HTTPS'] !== null){
	   		$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		}
		header("Location:".$goBackURL. "/bookDetail.php?bookNo=".$bookNo);
        	exit; 	
    	}   
?>

<?php

		try {
			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
			$db->createPdo();
			$sql = "INSERT INTO t_review(userId, no, reviewContent) VALUES(:userId, :bookNo, :reviewContent)";
			$insert = $db->dml($sql,[
				[':userId', $userId, PDO::PARAM_STR],
				[':bookNo', $bookNo, PDO::PARAM_STR],	
				[':reviewContent', $reviewContent, PDO::PARAM_STR]
			]);
		}catch(Exception $e) {
			echo '<span>データベースエラーがありました。</span>';
			echo '<a href="/bookDetail.php?bookNo='.$bookNo.'">書籍の詳細ページに戻る</a>';
		}
			
	
		$goBackURL = "https://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']);
	    if($_SERVER['HTTPS'] !== null){
		   	$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		}
		header("Location:".$goBackURL. "/bookDetail.php?bookNo=".$bookNo);
	    exit; 	
?>
