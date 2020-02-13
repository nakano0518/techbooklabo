<?php
    session_start();
    require_once'../lib/util.php';
    
    $userId = "testUser1";
    $password = "testUser1";
    $passwordh = password_hash($password, PASSWORD_DEFAULT);
    
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
        $sql = "SELECT * FROM t_users WHERE userId = :userId AND password = :password";
        $select = $pdo->prepare($sql);
        $select->bindValue(':userId', $userId, PDO::PARAM_STR);
        $select->bindValue(':password', $passwordh, PDO::PARAM_STR);
        $select->execute();
        $t_usetsData = $select->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
    
        $goBackURL = "https://".$_SERVER['HTTP_HOST'];
        header("Location:".$goBackURL. "/views/signIn.php");
        exit();    
    }
        $_SESSION['userId'] = $userId;
        header("Location:"."https://www.techbooklabo.com/index.php");
        exit();    
