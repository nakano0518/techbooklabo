<?php

/* ---------------------------------------------------------------------------
validation用の関数を自分で作成(ライブラリ化)
--------------------------------------------------------------------------- */
//未入力チェック
function checkInputEmpty($post) {
  if(empty($post)) {//empty()は引数が空白,Null,0ならtrueを返す
    return "× 入力は必須です。";
  }
}

//文字数が$length文字以内かチェック
function checkWordLength($post, $length) {
  if(strlen($post)> $length){
    return '× '.$length.'文字以内で入力してください。';
  }
}
  
//メールアドレスの形式チェック
function checkEmail($address) {
  if(!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/iD', $address)){
    return "× 不正な形式のアドレスです。";
}
}

//数値チェック
function isNumber($post) {
  if(preg_match("/[^0-9]+/", $post)) {
    return "× 数値以外が入力されました。";
  }
}

//パスワードの形式チェック：8文字以上の半角英数(大文字1文字以上含む)
function passwordFormat($password){
  if(!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,100}+\z/', $password)){
    return "× 指定の形式で入力してください。";
  }
}


//新しいパスワードと確認用パスワードの一致チェック
function confirmPassword($newEmail, $confirmEmail) {
  if($newEmail !== $confirmEmail) {
    return "× パスワードが一致しません。";
  }
}


//新規登録画面でユーザーIDに重複があった場合、エラーメッセージを取得する
function isDuplicateUserId($newUserId){
  $user = 'nakano';
	$dbpassword = '3114yashi';
	$dbName = 'BookReview';
	$host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
  $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
  //PDOクラスのインスタンスを作成し接続
  $pdo = new PDO($dsn, $user, $dbpassword);
  // プリペアドステートメントのエミュレーションを無効にする
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  // 例外がスローされる設定にする
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = 'SELECT userId FROM t_users WHERE userId = :userId';
  $stm = $pdo->prepare($sql);
  $stm->bindValue(':userId', $newUserId, PDO::PARAM_STR);  
  $stm->execute();
  $userIdData = $stm->fetchAll(PDO::FETCH_ASSOC);

  if($newUserId === '') {
    return 0;
  }else if($userIdData[0]['userId'] !== $newUserId){
    return false;
  }else{
    return true;
  }
}  
