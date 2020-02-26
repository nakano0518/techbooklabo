<?php
    session_start();
    session_regenerate_id(true);
    require_once '../lib/util.php';
    require_once '../vendor/autoload.php';
    $dotenv = Dotenv\Dotenv::createImmutable('../../env/');
    $dotenv->load();
    if(!isset($_SESSION['userId'])){
    	$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	if($_SERVER['HTTPS'] !== null){
   		$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	} 
        header('Location:'.$goBackURL.'/signIn.php');
	exit;
    }else {
        $userId = $_SESSION['userId'];
    }
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
    /* ---------------------------------------------------------------------
        いいねした本の情報の取得
    -----------------------------------------------------------------------*/
      try {
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = 'SELECT t_good.no, t_book.imageUrl, t_book.bookTitle 
        FROM t_good INNER JOIN t_users ON t_good.userId = t_users.userId 
        INNER JOIN t_book ON t_book.no = t_good.no WHERE t_users.userId = :userId';
        $select = $pdo->prepare($sql);
        $select->bindValue(':userId', $userId, PDO::PARAM_STR);
        $select->execute();
        $goodData = $select->fetchAll(PDO::FETCH_ASSOC);
      } catch(Exception $e) {
        echo '<span class="error">エラーがありました。</span><br>';
        echo $e->getMessage().'<br>';
      }
?>
<?php
    /* ---------------------------------------------------------------------
        レビューした本の情報とレビュー内容を取得する
    -----------------------------------------------------------------------*/
      try {
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = 'SELECT t_review.reviewId, t_review.no, t_book.imageUrl, t_book.bookTitle, t_review.reviewContent 
        FROM t_review INNER JOIN t_users ON t_review.userId = t_users.userId 
        INNER JOIN t_book ON t_review.no = t_book.no WHERE t_users.userId = :userId';
        $select = $pdo->prepare($sql);
        $select->bindValue(':userId', $userId, PDO::PARAM_STR);
        $select->execute();
        $reviewData = $select->fetchAll(PDO::FETCH_ASSOC);
      } catch(Exception $e) {
        echo '<span class="error">エラーがありました。</span><br>';
        echo $e->getMessage().'<br>';
      }
?>
<?php
/*----------------------------------------------------------------------
    ドロワーメニューに表示するユーザー名の取得 
----------------------------------------------------------------------*/
if(isset($_SESSION['userId'])||!empty($_SESSION['userId'])){
    try{
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = "SELECT name FROM t_users WHERE userId = :userId";
        $select = $pdo->prepare($sql);
        $select->bindValue(':userId', $userId, PDO::PARAM_STR);
        $select->execute();
        $name = $select->fetch(PDO::FETCH_ASSOC);
    }catch(Exception $e){
        echo $e->getMessage();
    }
}
?>
<?php
/* ----------------------------------------------------------------------
S3に保存した画像ファイルの読み取り
-----------------------------------------------------------------------*/
$s3 = new Aws\S3\S3Client(array(
    'version' => 'latest',
    'credwntials' => array(
        'key' => getenv('AWS_ACCESS_KEY_ID'),
        'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
    ),
    'region' => getenv('AWS_DEFAULT_REGION'),
));

$getObj = $s3->getObject(array(
              'Bucket' => getenv('AWS_BUCKET'),
              'Key'    => 'profileImages/'.basename($userData[0]['imageUrl']),
          ));
$image = $getObj['@metadata']['effectiveUri'];

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">    
<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Sawarabi+Gothic&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/mypageEdit.css">
    <title>TechBookLabo | <?php echo isset($name['name']) && !empty($name['name'])? es($name['name']).'さん' : '名称未設定さん'; ?>のプロフィール</title>
</head>
<body>
  <!-- ドロワーメニュー -->
        <div class="drawer">
            <div class="drawerMenu">
                <?php if(isset($userId) && !empty($userId)): ?>
                    <div class="signInMenu">
                        <p>ようこそ「<?php echo isset($name['name']) && !empty($name['name']) ? es($name['name']) : "名称未設定" ; ?>」さん</p>
                        <ul>
                            <li><a href="../index.php">ホーム</a></li>
                            <li><a href="./mypage.php">マイページ</a></li>
                            <li><a href="./signOut.php">ログアウト</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <div class="signOutMenu">
                        <p>ログイン or 新規登録</p>
                        <ul>
                            <li><a href="./signUp.php">新規登録</a></li>
                            <li><a href="./signIn.php">ログイン</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="cancelDrawerLayer">
            </div>
        </div>
    <header>
        <section class="header">
        <a href="../index.php">
            <img src="../images/booklogo.svg">
            <div class="headercenter">
                <span class="title">TechBookLabo</span>
            </div>
        </a>
        <div class="headerright">
            <i class="fas fa-bars"></i>
            <div class="menus">
                <ul>
                    <li><a href="./signUp.php">新規登録</a></li>
                    <li class="<?php echo $userId ? 'invisible': ''; ?>">
                        <a href="./signIn.php" class="<?php echo $userId ? 'invisible': ''; ?>">ログイン</a>
                    </li>
                    <li class="<?php echo $userId ? 'visible': 'invisible'; ?>">
                        <a href="./mypage.php" class="<?php echo $userId ? 'visible': 'invisible'; ?>">マイページ</a>
                    </li>
                    <li class="<?php echo $userId ? 'visible': 'invisible'; ?>">
                        <a href="./signOut.php" class="<?php echo $userId ? 'visible': 'invisible'; ?>">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
        </section>
    </header>
  <h1>「<span class="userTitleName"><?php echo $userData[0]['name']? es($userData[0]['name']) : '名前未設定'; ?></span>」さんの編集ページ</h1>
  <h2 class="userInfoTitle">ユーザー情報</h2>
    <?php if($_SESSION['updateComplete']):?>
        <p class="updateComplete"><?php echo $_SESSION['updateComplete'];?></p>
    <?php endif; ?>
    <?php if($_SESSION['validationErrors']):?>
        <p class="errorMessage">エラーがありました。</p>
    <?php endif; ?>
    <form action="./mypageEditProfileEdit.php" method ="post" enctype="multipart/form-data" class="userInfo">
        <div class="flexContainer-Profile-outline">
            <div class="flexContainer-Profile">
                <div class="flexItem-PhotoName">
                    <label>
                        <?php $imageUrl= es($userData[0]['imageUrl']); ?>
                        <img src='../images/userphoto/userDefault.jpg' class="userphoto userphoto1 <?php echo isset($imageUrl) && !empty($imageUrl)? 'displayNone': '' ?>" alt="プロフィール写真">
                        <img src='<?php echo isset($imageUrl) && !empty($imageUrl)? $image : "../images/userphoto/cameraIcon.jpeg"?>' class="userphoto userphoto2" alt="カメラの写真">
	            <input type="hidden" name="MAX_FILE_SIZE" value="500000">
		    <input type="file" name="file">
                    </label><br>
                    <label><input type="text" name="name" value="<?php echo $userData[0]['name']? es($userData[0]['name']) : '名前未設定'; ?>"> さん</label>
                </div>
                <div class="flexItem-Detail">
                    <label><span class="email">メールアドレス：</span><br><input type="email" name="email" value="<?php echo es($userData[0]['email']); ?>"></label>
                    <p class="errorText"><?php echo $_SESSION['validationErrors']['checkEmail'];?></p>
<div class="<?php echo $userId === 'testUser1'? 'invisible' : ''?>">
		    	<label><span class="password">新しいパスワード：</span><br><input type="password" name="password" placeholder="8文字以上半角英数(大文字含)"></label>
                    	<p class="errorText"><?php echo $_SESSION['validationErrors']['passwordFormat']; ?></p>
                    	<label><span class="passwordConfirm">確認用パスワード：</span><input type="password" name="passwordConfirm" placeholder="8文字以上半角英数(大文字含)"></label>
                    	<p class="errorText"><?php echo $_SESSION['validationErrors']['confirmPassword']; ?></p>
                    	<label class="checkbox"><input type="checkbox"><span class="check">パスワードを表示する</span></label>
</div>
                    <label><span class='experience'>実務経験：</span><br><input type="number" name="workYear" value="<?php echo es($userData[0]['workYear']) ?>"> <span class="experienceYear">年</span></label>
                    <p class="errorText"><?php echo $_SESSION['validationErrors']['isNumber']; ?></p>
                    <label><span class="language">得意な言語：</span><br><input type="text" name="language" value="<?php echo es($userData[0]['language']) ?>"></label><br>
                    <label><span class="comment">コメント：</span><br><textarea name="comment"><?php echo es($userData[0]['comment']) ?></textarea>
                </div>
            </div>
        </div>
	
	 <p class="fileSize <?php echo $_SESSION['fileSizeError'] == 2 ? 'fileSizeActive':''; ?>">※画像は500kB以内かつ拡張子(jpeg/png/gif)のみ</p>
	
        <input type="submit" value="ユーザー情報の変更">
    </form>
  <h2 class="goodTitle">お気に入りした本</h2>
     <?php if(!empty($goodData)):?>
        <div class="goodBookFlexContainer">
            <?php foreach($goodData as $goodDatum) : ?>
            <div class="goodBookFlexItem" data-id="<?php echo $userId; ?>" data-no="<?php echo es($goodDatum['no']); ?>">
                <i class="far fa-window-close" data-no="<?php echo es($goodDatum['no']); ?>"></i>
                <p><?php echo es($goodDatum['bookTitle']); ?></p>
                <a href="./bookDetail.php?bookNo=<?php echo es($goodDatum['no']); ?>">
                    <img src="<?php echo (substr($goodDatum['imageUrl'],0,1) == 'h')? es($goodDatum['imageUrl']) : '../images/'.es($goodDatum['imageUrl']); ?>" alt="本の画像">
                </a>
            </div>
            <?php endforeach;?>
            <div class="goodBookFlexItem empty"></div>
            <div class="goodBookFlexItem empty"></div>    
        </div>    
    <?php else: ?>
        <div class='noGoodBookContainer'>
            <p>お気に入りに追加した<br>書籍はありません。</p>
        </div>
    <?php endif; ?>
  <h2 class="reviewTitle">レビューした内容</h2>
    <?php if(!empty($reviewData)): ?>
      <div class="reviewBookFlexContainer">
        <?php foreach($reviewData as $reviewDatum) : ?>
            <div class="reviewBookFlexItem">
                <div class="bookItem">
                    <p><?php echo es($reviewDatum['bookTitle']); ?></p>
                    <a href="./bookDetail.php?bookNo=<?php echo es($reviewDatum['no']); ?>">
                        <img src="<?php echo (substr($reviewDatum['imageUrl'],0,1) == 'h')? es($reviewDatum['imageUrl']) : '../images/'.es($reviewDatum['imageUrl']); ?>" alt="本の画像">
                    </a>
                </div>
                <div class="reviewItem" data-id="<?php echo es($userId);?>" data-no="<?php echo es($reviewDatum['no']); ?>"  data-reviewid="<?php echo es($reviewDatum['reviewId']); ?>">
                    <form action="./mypageEditReview.php" method="post">
                        <input type="hidden" name="reviewId" value="<?php echo es($reviewDatum['reviewId']);?>">
                        <input type="hidden" name="bookNo" value="<?php echo es($reviewDatum['no']); ?>">
                        <textarea name="reviewContent"><?php echo es($reviewDatum['reviewContent']); ?></textarea>
                        <input type="submit" value="レビュー変更">
                    </form>
                    <i class="far fa-window-close" data-reviewid="<?php echo es($reviewDatum['reviewId']); ?>"></i>
                    
                </div>
            </div>
         <?php endforeach;?>
         <div class="reviewBookFlexItem reviewBookFlexItemEmpty"></div>
        </div>
    <?php else: ?>
        <div class='noReviewBookContainer'>
            <p>レビューした書籍はありません。</p>
        </div>
    <?php endif; ?>
   <div class="padding"></div>
    <footer>
        <p><small>&copy; 2019 TAICHI NAKANO</small></p>
    </footer> 
   <?php
    if($_SESSION['updateComplete']){
        $_SESSION['updateComplete'] = null;
    }
    
    if($_SESSION['validationErrors']){
        $_SESSION['validationErrors'] = null;
    }

    if($_SESSION['fileSizeError']){
	$_SESSION['fileSizeError'] = null;
    }
    ?>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="../js/mypageEdit.js"></script>
    <script src="../js/layout.js"></script>
</body>
</html>

