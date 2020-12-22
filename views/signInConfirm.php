<?php
    	session_start();
    
    
    	require_once '../config/envLoad.php';
    	require_once '../lib/util.php';
    	require_once '../lib/validation.php';
    


    	//CSRF(送信トークンとセッション保存トークンが一致するか確認)
    	if(isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
			//何もしない
		}else{
        	$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
			if($_SERVER['HTTPS'] !== null) {
				$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
			}
			header('Location:'.$goBackURL.'/signIn.php');
			exit;
		}
  

    	//エラー配列の用意
    	$errors = array();	

		//ユーザーID入力チェック
    	if(!isset($_POST['userId'])|| empty($_POST['userId'])){
        	$errors['userIdEmpty'] = "× ユーザーIDの入力は必須です。";
    	}else {
        	$userId = $_POST['userId'];
    	}

		//パスワード入力チェック
    	if(!isset($_POST['password'])|| empty($_POST['password'])){
        	$errors['passwordEmpty'] = "× パスワードの入力は必須です。";
    	}else {
        	$password = $_POST['password'];
    	}


	/*----------------------------------------------------------------------
    	データベースの接続
    ----------------------------------------------------------------------*/
    try {
        $db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
		$sql = "SELECT * FROM t_users WHERE userId = :userId";
		$t_usersData = $db->selectfetchAll($sql,[[':userId', $userId, PDO::PARAM_STR]]);
        if(count($t_usersData) === 0) {
            	$errors['userIdPasswordError'] = "× ユーザーIDかパスワードが間違っています。";
        }
		if(!password_verify($password, $t_usersData[0]['password'])) {
            	$errors['userIdPasswordError'] = "× ユーザーIDかパスワードが間違っています。";   
        }
   	}catch(Exception $e) {
		$errors['dbError'] = '× データベースエラーです。再入力してください。';
	}

	if(count($errors)>0){
    	$_SESSION['errors'] = $errors;
    	$_SESSION['userId'] = $userId;
    	$_SESSION['password'] = $password;
		$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		if($_SERVER['HTTPS'] !== null) {
			$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		}
		header('Location:'.$goBackURL.'/signIn.php');        
		exit;	    
	}else {
        $_SESSION['userId'] = $userId;
		$goBackURL = 'https://'.$_SERVER['HTTP_HOST'];
		if($_SERVER['HTTPS'] !== null) {
			$goBackURL = 'http://'.$_SERVER['HTTP_HOST'];
		}
		header('Location:'.$goBackURL.'/index.php');
		exit;   
    }

