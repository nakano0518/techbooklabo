
<?php
    session_start();
    require_once '../lib/util.php';
    require_once '../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable('../../env/');
    $dotenv->load();
    if(!isset($_SESSION['userId'])){
    //    $goBackURL = "http://".$_SERVER['HTTP_HOST'];
      //  header("Location:".$goBackURL. "/signIn.php");
	$userId = 'testUser1';
    }else {
        $userId = $_SESSION['userId'];
    }
 ini_set('display_errors', 1);
?>
<?php
    $user = 'nakano';
    $dbpassword = '3114yashi';
    $dbName = 'BookReview';
    $host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
    /* ---------------------------------------------------------------------
        ログインしているユーザーIDからユーザー名の取得
    -----------------------------------------------------------------------*/
    try {
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = 'SELECT * FROM t_users WHERE userId = :userId';
        $select = $pdo->prepare($sql);
        $select->bindValue(':userId', $userId, PDO::PARAM_STR);
        $select->execute();
        $userData = $select->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
        echo '<span class="error">エラーがありました。</span><br>';
        echo $e->getMessage().'<br>';
        
    }
?>
<?php
$s3 = new Aws\S3\S3Client(array(
    'version' => 'latest',
    'credwntials' => array(
        'key' => getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
    ),
    'region' => getenv('AWS_DEFAULT_REGION'),
));

$result = $s3->getObject(array(
              'Bucket' => getenv('AWS_BUCKET'),
              'Key'    => 'profileImages/'.basename($userData[0]['imageUrl']),
          ));
var_dump($result["@metadata"]['effectiveUri']);
echo '------------------------------------------------------------------';
var_dump($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <img src="<?php echo $result['@metadata']['effectiveUri']; ?>" alt="画像">
</body>
</html>
