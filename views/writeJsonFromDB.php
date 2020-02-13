<?php
require_once "../lib/util.php";

    $user = 'nakano';
    $dbpassword = '3114yashi';
    $dbName = 'BookReview';
    $host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
    try {
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = 'SELECT userId FROM t_users';
        $select = $pdo->prepare($sql);
        
        //実行し結果を取り出す
        $select->execute();
        $data = $select->fetchAll(PDO::FETCH_ASSOC);
        $data = json_encode($data);
        file_put_contents('../json/jsonfile/userId.json', $data);

        echo 'Success';
        if(!$data) {
           throw new Exception('データベースエラー');
        }
        
    }catch(Exception $e) {
        echo 'Fails';
    }

