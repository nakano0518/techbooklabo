<?php
	session_start();
	
	require_once "../lib/util.php";

	require_once '../vendor/autoload.php';
	$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
	$dotenv->load();


	if(isset($_POST['val'])){
    		$val = $_POST['val'];
	}
    

	try {
		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
    		$sql = "SELECT userId FROM t_users WHERE userId = :userId";
		$userIdData = $db->selectfetchAll($sql,[[':userId', $val, PDO::PARAM_STR]]);
    		
		if(!empty($userIdData)){
        		$isUserIdData = 1;//ユーザーIDは使用中⇒× 
    		}elseif($val==""){
        		$isUserIdData = 2;//入力が必須です
    		}else {
        		$isUserIdData = 0;//ユーザーIDは未使用⇒〇
    		}
	}catch(Exception $e){
    		echo $e->getMessage();
	}

	echo $isUserIdData;
