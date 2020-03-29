<?php
	session_start();
    	
	require_once "../lib/util.php";
  	
	require_once '../vendor/autoload.php';
	$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
	$dotenv->load();

    	if(isset($_POST) && !empty($_POST)){
        	$reviewId = es($_POST['reviewId']);
        	$bookNo = es($_POST['bookNo']);
        	$userId = es($_POST['userId']);
    	}

	try{
		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
    		$sql = "DELETE FROM t_review WHERE userId = :userId AND no = :no AND reviewId = :reviewId";
		$delete = $db->dml($sql,[		
    			[':userId', $userId, PDO::PARAM_STR],
    			[':no', $bookNo, PDO::PARAM_STR],
    			[':reviewId', $reviewId, PDO::PARAM_INT]
		]);
	}catch(Exception $e){
    		echo $e->getMessage();
	}

