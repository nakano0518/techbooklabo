<?php
session_start();
require_once "../lib/util.php";


if(isset($_POST['val'])){
    $val = $_POST['val'];
}
    $user = 'nakano';
    $dbpassword = '3114yashi';
    $dbName = 'BookReview';
    $host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
    try {
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
    	$sql = "SELECT userId FROM t_users WHERE userId = :userId";
    	$select = $pdo->prepare($sql);
    	$select->bindValue(':userId', $val, PDO::PARAM_STR);
    	//実行し結果を取り出す
    	$select->execute();
    	$userIdData = $select->fetchAll(PDO::FETCH_ASSOC);

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
?>
