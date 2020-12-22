<?php
	session_start();
	
	require_once '../config/envLoad.php';
	require_once "../lib/util.php";

	
	if(isset($_POST['bookNo']) && !empty($_POST['bookNo']) && isset($_POST['userId']) && !empty($_POST['userId'])){
  		$bookNo = $_POST['bookNo'];
  		$userId = $_POST['userId'];
	}else{
  		$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		if($_SERVER['HTTPS'] !== null) {
			$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		}
		header('Location:'.$goBackURL.'/signIn.php');
		exit;
	}

	//ログインユーザーのお気に入りの有無(データが存在するか)
    try {
		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
        	$sql = "SELECT delete_flg FROM t_good WHERE userId = :userId AND no = :bookNo";
		$starData = $db->selectfetchAll($sql,[
        		[':userId', $userId, PDO::PARAM_STR],
        		[':bookNo', $bookNo, PDO::PARAM_STR]
		]);
        $starData = $starData[0]['delete_flg'];
	}catch(Exception $e){
    	echo $e->getMessage();
	}


	if($starData === null) {
		//☆をクリックしたとき
		//データベースにデータが存在しない⇒データをINSERTしておく
		try{
			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
			$db->createPdo();
        		$sql = "INSERT INTO t_good(userId, no, delete_flg) VALUES(:userId, :bookNo, :delete_flg)";
			$insert = $db->dml($sql,[
        			[':userId', $userId, PDO::PARAM_STR],
        			[':bookNo', $bookNo, PDO::PARAM_STR],
        			[':delete_flg', 1, PDO::PARAM_INT]
			]);
		}catch(Exception $e){
    			echo $e->getMessage();
		}    
	}else {
    
		//★をクリックしたとき
		//データベースにデータが存在する⇒データをDELETEしておく
		try{
			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
			$db->createPdo();
			$sql = "DELETE FROM t_good WHERE userId = :userId AND no = :bookNo AND delete_flg = :delete_flg";
			$delete = $db->dml($sql,[
        			[':userId', $userId, PDO::PARAM_STR],
        			[':bookNo', $bookNo, PDO::PARAM_STR],
				[':delete_flg', 1, PDO::PARAM_INT]
			]);
		}catch(Exception $e){
    			echo $e->getMessage();
		}    
	}

	//お気に入りの総数の取得
	try{
		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
    	$sql = "SELECT COUNT(*) AS count FROM t_good WHERE no = :bookNo";
		$totalStarData = $db->selectfetchAll($sql,[':bookNo', $bookNo, PDO::PARAM_STR]);
    	$totalStarData = $totalStarData[0]['count'];
	}catch(Exception $e){
    	echo $e->getMessage();
	}


	echo $starData.','.$totalStarData;
	exit;


