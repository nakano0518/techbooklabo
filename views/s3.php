<?php 
//session_start();
//require_once "../vendor/autoload.php";
//$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
//$dotenv->load();
//use Aws\S3\S3Client;

//    $s3 = new S3Client(array(
//	'version' => 'latest',
//	'credentials' => array(
//		'key' => getenv('AWS_ACCESS_KEY_ID'),
//		'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
//	),
//	'region' => getenv('AWS_DEFAULT_REGION'),
  //  ));
　
　　//putObjectは失敗すると例外がスローされるため。try～catchで囲む
   // try{
//	$result = $s3->putObject(array(	
  //  		'ACL' => 'public-read', // 画像を一般公開設定にしておく
    //		'Bucket' => getenv('AWS_BUCKET'),//バケット名
    //		'Key' => time() . '.jpg',//バケット内のファイル名
//		'SourceFile' => $_FILES['file']['tmp_name'],//元のファイル
  //  		'ContentType' => mime_content_type($_FILES['file']['tmp_name']),
//	));
  //       $url = $result['ObjectURL'];//アップロードしたファイルのURLが取得できる	
    //} catch(S3Exception $e){
//	echo $e->getMessage();
//	echo '<a href="./s3_form.php">前のページに戻る</a>'; 	
  //  } 
//));
//?>

