<?php
session_start();
session_regenerate_id(true);
require_once '../lib/validation.php';
if(isset($_POST) && !empty($_POST)) {
    $newUserId = $_SESSION['newUserId'] = $_POST['newUserId'];
    $password = $_SESSION['password'] = $_POST['password'];
    $passwordConfirm = $_SESSION['passwordConfirm'] = $_POST['passwordConfirm'];
}

/* ---------------------------------------------------------------------
バリデーション
----------------------------------------------------------------------*/

//エラー配列
$validationErrors = array();

//ユーザーIDとパスワード(確認用パスワード)は必須項目
if(checkInputEmpty($newUserId)){
    $validationErrors['checkInputEmpty_newUserId'] = '× ユーザーIDの入力は必須です。';
}
if(checkInputEmpty($password)){
    $validationErrors['checkInputEmpty_password'] = '× パスワードの入力は必須です。';
}
if(checkInputEmpty($passwordConfirm)){
    $validationErrors['checkInputEmpty_passwordConfirm'] = '× 確認用パスワードの入力は必須です。';
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


if(count($validationErrors) > 0) {
    $_SESSION['validationErrors'] = $validationErrors;
    //リダイレクト
    $goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    if($_SERVER['HTTPS'] !== null){
        $goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    }
    header('Location:'.$goBackURL.'/signUp.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/signUpConfirm.css">
    <title>Document</title>
</head>
<body>
    <section class="signInWhole">
        <div class="signIn">
            <div class="logo">
                <img src="../images/booklogo.svg">
                <span class="title">TechBookLabo</span>
            </div>
            <h1>下記内容で<br>よろしいでしょうか</h1>
            <div class="contents">
                <p class="userId"><span>ユーザーID：</span><span><?php echo $newUserId;?></span></p> 
                <p class="password"><span>パスワード：</span><span><?php echo substr($password, 0, 2).str_repeat('*', mb_strlen($password)-2);?></span></p>
            </div>
            <div class="link">
                <a href="./signUp.php">訂正</a>
                <a href="./signUpComplete.php">登録</a>
            </div>
        </div>
    </section>
</body>
</html>
