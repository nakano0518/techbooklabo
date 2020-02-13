<?php
    session_start();
    require_once '../lib/util.php';
    require_once '../lib/validation.php';
    
    //CSRF(送信トークンとセッション保存トークンが一致するか確認)
    if(isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
	//何もしない
}else{
        $goBackURL = "https//".$_SERVER['HTTP_HOST'];
        header("Location:".$goBackURL. "/views/signIn.php");
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
    $user = 'nakano';
    $dbpassword = '3114yashi';
    $dbName = 'BookReview';
    $host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
    try {
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
	$sql = "SELECT * FROM t_users WHERE userId = :userId";
        $select = $pdo->prepare($sql);
        $select->bindValue(':userId', $userId, PDO::PARAM_STR);
        $select->execute();
        $t_usersData = $select->fetchAll(PDO::FETCH_ASSOC);
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
        $goBackURL = "https://".$_SERVER['HTTP_HOST'];
        header("Location:".$goBackURL. "/views/signIn.php");
	exit();    
    }else {
        $_SESSION['userId'] = $userId;
        $goBackURL = "https://".$_SERVER['HTTP_HOST'];
        header("Location:".$goBackURL. "/index.php");
	exit();   
    }

