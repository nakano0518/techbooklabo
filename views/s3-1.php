<?php 
session_start();
require_once "../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
$dotenv->load();

echo getenv('APP_NAME');
echo 'b';
$s3client = new Aws\S3\S3Client([
    'credentials' => [
        'key' => getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
    ],
    'region' => getenv('AWS_DEFAULT_REGION'), 
    'version' => 'latest',
]);

echo $_FILES['file']['tmp_name'];
echo getenv('AWS_DEFAULT_REGION');
var_dump($_FILES['file']);

ini_set('display_errors', 1);
try {
	echo 'a1'; 
    $result = $s3client->putObject([
        'ACL' => 'private', 
        'Bucket' => getenv('AWS_BUCKET'), // バケット名
        'Key' => 'profileImages/'.date('YmdHis').$_FILES['file']['name'], // バケットのルートからのパス
//	'Body' => fopen($_FILES['file']['tmp_name'],'rb'),//バイナリで読み取る
        'SourceFile' => $_FILES['file']['tmp_name'],
	'ContentType' => mime_content_type($_FILES['file']['tmp_name']),
    ]);
	echo 'a2';
    $url = $result['ObjectURL']; // アップロードしたファイルのURLが取得できる
    // do success action
 echo 'success';
var_dump($result);
} catch (Aws\S3\Exception\S3Exception $e) {
    // do failure action
    echo 'exception';
}
echo 's3Finish';
?>

