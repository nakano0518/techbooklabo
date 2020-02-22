<?php
    require_once "../lib/util.php";
    session_start();

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
        exit(); 	
    }
    
?>
<?php
$user = 'nakano';
$dbpassword = '3114yashi';
$dbName = 'BookReview';
$host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

try {
$pdo = new PDO($dsn, $user, $dbpassword);
// プリペアドステートメントエミュレーションを無効にする
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
// 例外がスローされる設定にする
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//t_bookテーブルに入力値を保存。
$sql = "INSERT INTO t_review(userId, no, reviewContent) 
VALUES(:userId, :bookNo, :reviewContent)";
$insert = $pdo->prepare($sql);
$insert->bindValue(":userId", $userId, PDO::PARAM_STR);
$insert->bindValue(":bookNo", $bookNo, PDO::PARAM_STR);
$insert->bindValue(":reviewContent", $reviewContent, PDO::PARAM_STR);
//実行し結果を取り出す
$insert->execute();
} catch(Exception $e) {
echo '<span class="error">エラーがありました。</span><br>';
echo $e->getMessage().'<br>';
echo '<a href="./bookDetail.php?bookNo='.$bookNo.'">書籍詳細ページに戻る</a>';
exit;
}
?>
<?php
$goBackURL = "http://".$_SERVER['HTTP_HOST'];
header("Location:".$goBackURL. "/views/bookDetail.php?bookNo=$bookNo");
exit;
?>
