<?php
	session_start();
	session_regenerate_id(true);

	require_once "../lib/validation.php";
	require_once "../lib/util.php";
	
	require_once '../vendor/autoload.php';
	$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
	$dotenv->load();

	//エラー格納用変数の設定
	$errors = array();

	if((isset($_SESSION['newUserId']) && !empty($_SESSION['newUserId']))&&(isset($_SESSION['password']) && !empty($_SESSION['password']))) {
   		$newUserId = es($_SESSION['newUserId']);
   		$password = es($_SESSION['password']);
   		$passwordh = password_hash($password, PASSWORD_DEFAULT);
	}else {
   		$errors[] = "ユーザIDかパスワードが未設定です。";
	}
?>



<!DOCTYPE html>
<html lang="ja">
	<head>
  		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<meta http-equiv="X-UA-Compatible" content="ie=edge">
  		<link rel="stylesheet" href="../css/signUpComplete.css">
  		<title>登録完了</title>
	</head>
	<body>
		<div>
  			<?php
    				try {
					$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
					$db->createPdo();
        				$sql = "INSERT INTO t_users(userId, password)VALUES(:userId, :password)";	
					$insert = $db->dml($sql,[
        					[':userId', $newUserId, PDO::PARAM_STR],
      						[':password', $passwordh, PDO::PARAM_STR]
					]);
      				}catch(Exception $e){
$errorContent = <<< EOD
<section class="errorWhole">
<div class="errorSection">
<div class="logo">
<img src="../images/booklogo.svg">
<span class="title">TechBookLabo</span>
</div>
<h1>エラーがありました</h1>
<div class="contents">
<span class="error">データベースエラー</span><br>  
</div>
<div class="link">
<a href="./signUp.php">最初に戻る</a>
</div>
</div>
</section>
EOD;

					//出力
					echo $errorContent;      
				}  
?>

			<?php if(count($errors) > 0): ?>
  				<section class="errorWhole">
    					<div class="errorSection">
      						<div class="logo">
        						<img src="../images/booklogo.svg">
        						<span class="title">TechBookLabo</span>
      						</div>
      						<h1>エラーがありました</h1>
      						<div class="contents">
        						<span class="error"><?php implode('<br>', $errors);?></span><br>  
      						</div>
      						<div class="link">
        						<a href="./signUp.php">最初に戻る</a>
      						</div>
    					</div>
				</section>
			<?php else: ?>
  				<section class="signInWhole">
        				<div class="signIn">
            					<div class="logo">
                					<img src="../images/booklogo.svg">
                					<span class="title">TechBookLabo</span>
            					</div>
            					<h1>下記内容で登録しました</h1>
            					<div class="contents">
                					<p class="newUserId"><span>ユーザーID：</span><span><?php echo $newUserId;?></span></p> 
                					<p class="password">
                  						<span>パスワード：</span>
                  						<span>
                      							<?php echo substr($password, 0, 2).str_repeat('*', mb_strlen($password)-2); ?>
                  						</span>
                					</p>
            					</div>
            					<div class="link">
                					<a href="./signIn.php">ログイン</a>
            					</div>
        				</div>
    				</section>
			<?php endif; ?>
		</div>
	</body>
</html>
