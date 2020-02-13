<?php 
session_start();
require_once "../lib/util.php";
require_once "../lib/validation.php";

if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}else {
    $goBackURL = "http://".$_SERVER['HTTP_HOST'];
    header("Location:".$goBackURL. "/views/signIn.php");
}
if(isset($_POST) && !empty($_POST)) {
    $name = es($_POST['name']);
    $password = es($_POST['password']);
    $passwordh = password_hash($password, PASSWORD_DEFAULT);
    $passwordConfirm = es($_POST['passwordConfirm']);
    $email = es($_POST['email']);
    $workYear = es($_POST['workYear']);
    $language = es($_POST['language']);
    $comment = es($_POST['comment']);
}
//テストユーザー(デモユーザー)のパスワードは勝手に変更されてはならない
//mypageEdit.phpではパスワード入力欄は非表示にして、
//本ファイルでUPDATEする際に必要なパスワードのデータを用意しておく
if($userId === "testUser1") {
    $password = "testUser1";
    $passwordh = password_hash($password, PASSWORD_DEFAULT);
    $passwordConfirm = "testUser1";
}
/* -----------------------------------------------------------------------------
入力値に対するバリデーション
----------------------------------------------------------------------------- */
//エラーメッセージ格納用の配列を用意
$validationErrors = array();

//メールアドレスの形式チェック⇒エラーメッセージ追加
if(!is_null(checkEmail($email))){
    $validationErrors['checkEmail'] = checkEmail($email);
}

//確認用パスワードとの一致チェック⇒エラーメッセージ追加
if(!is_null(confirmPassword($password, $passwordConfirm))){
    $validationErrors['confirmPassword'] = confirmPassword($password, $passwordConfirm);
}


//パスワードの形式チェック：8文字以上の半角英数(大文字1文字以上含む)
//⇒エラーメッセージ追加
if(!is_null(passwordFormat($password))){
    $validationErrors['passwordFormat'] = passwordFormat($password);
}

//数値チェック⇒エラーメッセージ追加
if(!is_null(isNumber($workYear))){
    $validationErrors['isNumber'] = isNumber($workYear);
}


//エラーメッセージ格納用配列が空でなければ
//エラーメッセージをセッションに保存し、編集ページにリダイレクト
if(count($validationErrors)>0) {
    $_SESSION['validationErrors'] = $validationErrors;
    $goBackURL = "http://".$_SERVER['HTTP_HOST'];
    header("Location:".$goBackURL. "/views/mypageEdit.php");
    exit();
}

if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
    $imageUrl = $_FILES['file']['name'];
    $uploadDir = '../images/userphoto/';
    $upload = $uploadDir.basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $upload);
}else {
    $user = 'nakano';
    $dbpassword = '3114yashi';
    $dbName = 'BookReview';
    $host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
    try{
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
	$sql = "SELECT * FROM t_users WHERE userId = :userId";
        $select = $pdo->prepare($sql);
        $select->bindValue(':userId', $userId, PDO::PARAM_STR);
        $select->execute();
	$data = $select->fetchAll(PDO::FETCH_ASSOC);
	foreach($data as $datum) {
            $imageUrl = $datum['imageUrl'];
        }
    }catch(Exception $e){
        $e->getMessage();
    }
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
        $sql = "UPDATE t_users SET name = :name, password = :password, email = :email, workYear = :workYear, language = :language, imageUrl = :imageUrl, comment = :comment WHERE userId = :userId;";
        $update = $pdo->prepare($sql);
        $update->bindValue(':name', $name, PDO::PARAM_STR);
        $update->bindValue(':password', $passwordh, PDO::PARAM_STR);
        $update->bindValue(':email', $email, PDO::PARAM_STR);
        $update->bindValue(':workYear', $workYear, PDO::PARAM_INT);
        $update->bindValue(':language', $language, PDO::PARAM_STR);
        $update->bindValue(':imageUrl', $imageUrl, PDO::PARAM_STR);
        $update->bindValue(':comment', $comment, PDO::PARAM_STR);
        $update->bindValue(':userId', $userId, PDO::PARAM_STR);
        $update->execute();
        $_SESSION['updateComplete'] = "更新が完了しました。";
        $goBackURL = "http://".$_SERVER['HTTP_HOST'];
        header("Location:".$goBackURL. "/views/mypageEdit.php");
    }catch(Exception $e){
        echo $e->getMessage();
    }
?>

