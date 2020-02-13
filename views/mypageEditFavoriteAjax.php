<?php
    session_start();
    require_once "../lib/util.php";


    if(isset($_POST) && !empty($_POST)){
        $bookNo = es($_POST['bookNo']);
        $userId = es($_POST['userId']);
    }
?>
<?php
$user = 'nakano';
$dbpassword = '3114yashi';
$dbName = 'BookReview';
$host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
try{
    $pdo = new PDO($dsn, $user, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
    $sql = "DELETE FROM t_good WHERE userId = :userId AND no = :no";
    $delete = $pdo->prepare($sql);
    $delete->bindValue(':userId', $userId, PDO::PARAM_STR);
    $delete->bindValue(':no', $bookNo, PDO::PARAM_STR);
    //実行し結果を取り出す
    $delete->execute();
}catch(Exception $e){
    echo $e->getMessage();
}
?>
