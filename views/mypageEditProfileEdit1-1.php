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

//画像のバイト数チェック
if($_FILES['file']['error'] === 2) {
	$_SESSION['fileSizeError'] = '1024kB以上のファイルはアップロードできません。';
	$goBackURL = "http://".$_SERVER['HTTP_HOST'];
	header("Location:".$goBackURL."/views/mypageEdit.php");
	exit();
}

//ファイル名の確定
$img_name = "img_".$date_str.$extension;
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
	
}catch(Aws\S3\S3Exception $e) {
echo 'error!!';
}
echo 'finished';
}else{
 echo 'else';
}
?>

