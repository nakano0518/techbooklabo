<?php 
session_start();
require_once "../lib/util.php";

if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}else {
	$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	if($_SERVER['HTTPS'] !== null){
   		$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	}
	header('Location:'.$goBackURL.'/signIn.php');
	exit;   
}

if(isset($_POST) && !empty($_POST)) {
    $bookNo = es($_POST['bookNo']);
    $reviewId = es($_POST['reviewId']);
    $reviewContent = es($_POST['reviewContent']);
}
$user = 'nakano';
$dbpassword = '3114yashi';
$dbName = 'BookReview';
$host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
try{
    $pdo = new PDO($dsn, $user, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
    $sql = "UPDATE t_review SET reviewContent = :reviewContent WHERE userId = :userId AND no = :no AND reviewId = :reviewId";
    $update = $pdo->prepare($sql);
    $update->bindValue(':userId', $userId, PDO::PARAM_STR);
    $update->bindValue(':no', $bookNo, PDO::PARAM_STR);
    $update->bindValue(':reviewId', $reviewId, PDO::PARAM_INT);
    $update->bindValue(':reviewContent', $reviewContent, PDO::PARAM_STR);
    //実行し結果を取り出す
    $update->execute();
    $goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    if($_SERVER['HTTPS'] !== null){
        $goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    }
    header('Location:'.$goBackURL.'/mypageEdit.php');
    exit;
}catch(Exception $e){
    echo $e->getMessage();
}
    
