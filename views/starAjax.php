<?php
session_start();
require_once "../lib/util.php";


if(isset($_POST['bookNo']) && !empty($_POST['bookNo']) && isset($_POST['userId']) && !empty($_POST['userId'])){
  $bookNo = $_POST['bookNo'];
  $userId = $_POST['userId'];
}else{
  $goBackURL = "http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF']);
   header("Location:".$goBackURL. "/signIn.php");
   exit();
}

//ログインユーザーのお気に入りの有無(データが存在するか)
    $user = 'nakano';
    $dbpassword = '3114yashi';
    $dbName = 'BookReview';
    $host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
    
    try {
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = "SELECT delete_flg FROM t_good WHERE userId = :userId AND no = :bookNo";
        $select = $pdo->prepare($sql);
        $select->bindValue(':userId', $userId, PDO::PARAM_STR);
        $select->bindValue(':bookNo', $bookNo, PDO::PARAM_STR);
        $select->execute();
        $starData = $select->fetchAll(PDO::FETCH_ASSOC);
        $starData = $starData[0]['delete_flg'];
}catch(Exception $e){
    echo $e->getMessage();
}
echo $starData;


if($starData === null) {
    //☆をクリックしたとき
    //データベースにデータが存在しない⇒データをINSERTしておく
    try{
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = "INSERT INTO t_good(userId, no, delete_flg) 
    VALUES(:userId, :bookNo, :delete_flg)";
        $insert = $pdo->prepare($sql);
        $insert->bindValue(':userId', $userId, PDO::PARAM_STR);
        $insert->bindValue(':bookNo', $bookNo, PDO::PARAM_STR);
        $insert->bindValue(':delete_flg', 1, PDO::PARAM_INT);
        $insert->execute();
}catch(Exception $e){
    echo $e->getMessage();
}    
}else {
    
    //★をクリックしたとき
    //データベースにデータが存在する⇒データをDELETEしておく
    try{
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
	$sql = "DELETE FROM t_good WHERE userId = :userId AND no = :bookNo AND delete_flg = :delete_flg";
        $delete = $pdo->prepare($sql);
        $delete->bindValue(':userId', $userId, PDO::PARAM_STR);
        $delete->bindValue(':bookNo', $bookNo, PDO::PARAM_STR);
	$delete->bindValue(':delete_flg', 1, PDO::PARAM_INT);
        $delete->execute();
}catch(Exception $e){
    echo $e->getMessage();
}    
}

//お気に入りの総数の取得
    
try{
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = "SELECT COUNT(*) AS count FROM t_good WHERE no = :bookNo";
        $select = $pdo->prepare($sql);
        $select->bindValue(':bookNo', $bookNo, PDO::PARAM_STR);
        $select->execute();
        $totalStarData = $select->fetchAll(PDO::FETCH_ASSOC);
        $totalStarData = $totalStarData[0]['count'];
}catch(Exception $e){
    echo $e->getMessage();
}


echo $starData.','.$totalStarData;
exit;
?>


