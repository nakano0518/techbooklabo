<?php 
session_start();
require_once "../vendor/autoload.php";
require_once "../lib/util.php";
require_once "../lib/validation.php";
$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
$dotenv->load();

//ユーザーIDセッションチェック
if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
	$userId = $_SESSION['userId'];
}else {
	$goBackURL = "http://".$_SERVER['HTTP_HOST'];
	header("Location:".$goBackURL."/views/signIn.php");
	exit();
}

//POST値チェック
if(isset($_POST) && !empty($_POST)) {
	$name = es($_POST['name']);
	$passsword = es($_POST['password']);
	$passwordh = password_hash($password, PASSWORD_DEFAULT);
	$passwordConfirm = es($_POST['passwordComfirm']);
	$email = es($_POST['email']);
	$workYear = es($_POST['workYear']);
	$language = es($_POST['language']);
	$comment = es($_POST['comment']);
}

//テストユーザー(デモユーザー)のパスワードは勝手に変更されてはならない
//mypageEdit.phpではパスワード欄は非表示にして
//本ファイルでUPDATEする際に必要なパスワードのデータを用意しておく
if($userId === 'testUser1') {
	$password = 'testUser1';
	$passwordh = password_hash($password, PASSWORD_DEFAULT);
	$passwordConfirm = "testUser1";
}

/*-----------------------------------------------------------------------
入力値に対するバリデーション
------------------------------------------------------------------------*/
//エラーメッセージ格納用の配列用意
$validationErrors = array();

//メールアドレス形式チェック→エラーメッセージ追加
if(!is_null(checkEmail($email))){
	$validatiomErrors['checkEmail'] = checkEmail($email);
}

//確認用パスワードとの一致チェック→エラーメッセージ追加
if(!is_null(confirmPassword($password, $passwordConfirm))){
	$validationErrors['confirmPassword'] = confirmPassword($password, $passwordConfirm);
}


//パスワードの形式チェック:8文字以上の半角英数(大文字1文字以上含む)
//→エラーメッセージ追加
if(!is_null(passwordFormat($password))){
	$validationErrors['passwordFormat'] = passwordFormat($password);
}

//数値チェック
if(!is_null(isNumber($workYear))){
	$validationErrors['isNumber'] = isNumber($workYear);
}

//エラーメッセージ格納用配列が空でなければ、
//エラーメッセージをセッションに保存し、編集ページにリダイレクト
if(count($validationErrors)>0){
	$_SESSION['validationErrors'] = $validationErrors;
	$goBackURL = "http://".$_SERVER['HTTP_HOST'];
	header("Location:".$goBackURL."/views/mypageEdit.php");
	exit();
}


//ファイル名
$img_name = $_FILES['file']['name'];
//ファイルパス
$img_path = $_FILES['file']['tmp_name']; 
$date_str = ceil(microtime(true)*1000);

//画像のバイト数チェック

if($_FILES['file']['error'] == 2) {
	$_SESSION['fileSizeError'] = $_FILES['file']['error'];
	$goBackURL = "http://".$_SERVER['HTTP_HOST'];
	header("Location:".$goBackURL."/views/mypageEdit.php");
	exit();
}

//拡張子取得
//imagetype($img_path)でImagetype定数(IMAGETYPE_JPEGなど)を返却
//image_type_to_mime_type(Imagetypet定数)でmimetype(image/jpeg)を返却
$img_type = image_type_to_mime_type(exif_imagetype($img_path));
//拡張子チェック
if(preg_match("/gif/i", $img_type) == 1) {
	$extension = ".gif";
}else if(preg_match("/png/i", $img_type) == 1) {
	$extension = ".png";
}else if(preg_match("/jpeg/i", $img_type) == 1){
	$extension = ".jpeg";
}else {
	$goBackURL = "http://".$_SERVER['HTTP_HOST'];
	header("Location:".$goBackURL."/views/mypageEdit.php");
	exit();
}


//ファイル名の確定
$img_name = "img_".$date_str.$extension;

//一時フォルダに画像があればS3に追加しそのURLを取得しDBへ保存、
//なければ、もともとDBに格納された画像URLを取得しDBへ再格納。
$imageUrl = "";
if(isset($img_path) && !empty($img_path)){
$s3 = new Aws\S3\S3Client(array(
	'version' => 'latest',
	'credentials' => array(
		'key' => getenv('AWS_ACCESS_KEY_ID'),
		'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
	),
	'region' => getenv('AWS_DEFAULT_REGION'),
)); 

//putObject()は失敗時例外を発生するのでtry～catchで囲む
try {
	$result = $s3->putObject(array(
		'ACL' => 'private',
		'Bucket' => getenv('AWS_BUCKET'),
		'Key' => "profileImages/".$img_name,
		'SourceFile' => $img_path,
		'ContentType' => mime_content_type($img_path),
	));
	$imageUrl = $result['ObjectURL'];	
}catch(Aws\S3\S3Exception $e) {
	$goBackURL = "http://".$_SERVER['HTTP_HOST'];
	header("Location:".$goBackURL."/views/mypageEdit.php");
	exit();
}
echo 'finished';
}else{
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
	$goBackURL = "http://".$_SERVER['HTTP_HOST'];
	header("Location:".$goBackURL."/views/mypageEdit.php");
	exit();
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
	$goBackURL = "http://".$_SERVER['HTTP_HOST'];
	header("Location:".$goBackURL."/views/mypageEdit.php");
	exit();
    }
?>

