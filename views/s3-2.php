<?php 
session_start();
require_once "../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
$dotenv->load();

//ファイル名
$img_name = $_FILES['file']['name'];
//ファイルパス
$img_path = $_FILES['file']['tmp_name']; 


//画像の大きさ指定
$img_max_height = "350";
$img_max_width = "350";

//拡張子取得
//imagetype($img_path)でImagetype定数(IMAGETYPE_JPEGなど)を返却
//image_type_to_mime_type(Imagetypet定数)でmimetype(image/jpeg)を返却
$img_type = image_type_to_mime_type(exif_imagetype($img_path));

//画像の大きさを取得
list($img_width, $img_height) = getimagesize($img_path);

//ミリ秒まで取得(ファイル名で使用する)
$date_str = ceil(microtime(true)*1000);
echo $img_name;
echo $img_type;
echo $date_str;
var_dump(preg_match('/jpeg/i', $img_type));

if(preg_match('/gif/i', $img_type) == 1 || preg_match('/png/i', $img_type) == 1 || preg_match('/jpeg/i', $img_type) == 1) {

//画像の大きさが設定値以内であればそのままの大きさ
//それ以上であれば、設定値まで縮小
$resize_height = 0;
$resize_width = 0;
if(($img_max_height > $img_height) && ($img_max_width > $img_width)) {
	$resize_height = $img_height;
	$resize_width = $img_width;
} else {
	$per = 1.0;	
	$resize_height = $img_height;	
	$resize_width = $img_width;
	while($img_max_width < $resize_width) {
		$resize_height = floor($resize_height * $per);
		$resize_width = floor($resize_width * $per);
		$per = $per - 0.1;
	}
	while($img_max_height < $resize_height) {
		$resize_height = floor($resize_height * $per);
		$resize_width = floor($resize_width * $per);
		$per = $per - 0.1;
	}
}

//失敗した場合FALSE、成功した場合画像リソースIDが返却
$thumb_image = imagecreatetruecolor($resize_width, $resize_height);
imagecolorallocate($thumb_image, 255, 255, 255, 0.1);
imagealphablending($thumb_image, false);
imagesavealpha($thumb_image, true);

if(preg_match("/gif/i", $img_type) == 1) {
	$extension = ".gif";
	$original_image = imagecreatefromgif($img_path);
}else if(preg_match("/png/i", $img_type) == 1) {
	$extension = ".png";
	$original_image = imagecreatefrompng($image_path);
}else if(preg_match("/jpeg/i", $img_type) == 1){
	$extension = ".jpeg";
	$original_image = imagecreatefromjpeg($image_path);
}

//ファイル名の確定
$img_name = "img_".$date_str.$extension;
echo $img_name;
//サムネイル画像の作成
//コピー先の画像リソース、コピー元の画像リソース、コピー先x座標、コピー先y座標、コピー元x座標、コピー元y座標、コピー先の幅、コピー先の高さ、コピー元の幅、コピー元の高さ
//$thumb_image(コピー先の画像ファイルパス)がリサイズされる。
imagecopyresampled($thumb_image, $original_image, 0, 0, 0, 0, $resize_width, $resize_height, $img_width, $img_height);


if($extension == ".gif"){
	imagegif($thumb_image, dirname($img_path).'/'.$img_name, 60);
}else if($extension == ".png") {
	imagepng($thumb_image, dirname($img_path).'/'.$img_name, 60);
}else if($extension == ".jpeg") {
	imagejpeg($thumb_image, dirname($img_path).'/'.$img_name, 60);
}
}

$img_path = dirname($img_path).'/'.$img_name;

echo $img_name;
echo $img_path;
echo $thumb_image;

imagedestroy($thumb_image);
imagedestroy($original_image);

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

?>

