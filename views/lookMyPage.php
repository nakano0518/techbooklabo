<?php
		session_start();
    	session_regenerate_id(true);
    	
    	require_once '../config/envLoad.php';
    	require_once '../lib/util.php';
    	


    	if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
		$userId = $_SESSION['userId'];
    	}
    

    	if(isset($_GET['followId']) && !empty($_GET['followId'])){
		$followId = $_GET['followId'];	
    	}else{
   		$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		if($_SERVER['HTTPS'] !== null){
   			$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		}
		header('Location:'.$goBackURL.'/signIn.php');
		exit;
    	}
?>
<?php
	/* ---------------------------------------------------------------------
        ログインしているユーザーIDからユーザー名の取得
	-----------------------------------------------------------------------*/
    	try {
        	$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
        	$sql = 'SELECT * FROM t_users WHERE followId = :followId';
		$userData = $db->selectfetchAll($sql,[[':followId', $followId, PDO::PARAM_INT]]);
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
        	$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
        	$sql = 'SELECT t_good.no, t_book.imageUrl, t_book.bookTitle FROM t_good INNER JOIN t_users ON t_good.userId = t_users.userId INNER JOIN t_book ON t_book.no = t_good.no WHERE t_users.followId = :followId';
		$goodData = $db->selectfetchAll($sql,[[':followId', $followId, PDO::PARAM_INT]]);
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
        	$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
        	$sql = 'SELECT t_review.reviewId, t_review.no, t_book.imageUrl, t_book.bookTitle, t_review.reviewContent FROM t_review INNER JOIN t_users ON t_review.userId = t_users.userId INNER JOIN t_book ON t_review.no = t_book.no WHERE t_users.followId = :followId';
		$reviewData = $db->selectfetchAll($sql,[[':followId', $followId, PDO::PARAM_INT]]);
      	} catch(Exception $e) {
        	echo '<span class="error">エラーがありました。</span><br>';
        	echo $e->getMessage().'<br>';
      	}
?>
<?php
	/*----------------------------------------------------------------------
    	モバイル用ドロワーメニューに表示するユーザー名の取得 
	----------------------------------------------------------------------*/
	if(isset($_SESSION['userId'])||!empty($_SESSION['userId'])){
    		try{
        		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
			$db->createPdo();
        		$sql = "SELECT name FROM t_users WHERE userId = :userId";
			$name = $db->selectfetch($sql,[[':userId', $userId, PDO::PARAM_STR]]);
    		}catch(Exception $e){
        		echo '<span class="error">エラーがありました。</span><br>';
        		echo $e->getMessage();
   	 	}
	}
?>

<?php
	/*---------------------------------------------------------------------
	S3から画像の読み取り
	---------------------------------------------------------------------*/
    	require_once '../vendor/autoload.php';
    	$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
    	$dotenv->load();

	$s3 = new Aws\S3\S3Client(array(
    		'version' => 'latest',
    		'credentials' => array(
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
    		<link rel="stylesheet" href="../css/mypage.css">
    		<title>TechBookLabo | <?php echo isset($name['name']) && !empty($name['name'])? es($name['name']).'さん' : '名称未設定さん'; ?>のプロフィール</title>
	</head>
	<body>
     		<!-- モバイル用ドロワーメニューここから -->
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
			    			<li><a href="../index.php">ホーム</a></li>
                            			<li><a href="./signUp.php">新規登録</a></li>
                            			<li><a href="./signIn.php">ログイン</a></li>
                        		</ul>
                    		</div>
                		<?php endif; ?>
            		</div>
            		<div class="cancelDrawerLayer">
            		</div>
        	</div>
     		<!-- モバイル用ドロワーメニューここまで -->
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
                        					<a href="./signOut.php" class="<?= $userId ? 'visible': 'invisible'; ?>">ログアウト</a>
                    					</li>
                				</ul>
            				</div>
        			</div>
        		</section>
    		</header>
		
		<main>
  			<h1>「<span class="userTitleName"><?php echo $userData[0]['name']? es($userData[0]['name']) : '名前未設定'; ?></span>」さんのマイページ</h1>
  			<h2 class="userInfoTitle">ユーザー情報</h2>
        		<div class="flexContainer-Profile-outline">
            			<div class="flexContainer-Profile">
                			<div class="flexItem-PhotoName">
                    				<?php $imageUrl= es($userData[0]['imageUrl']); ?>
                    					<img src='<?php echo $imageUrl? $image : "../images/userphoto/userDefault.jpg" ?>' class="userphoto" alt="プロフィール写真">
                    					<p class="userName"><?php echo $userData[0]['name']? es($userData[0]['name']) : '名前未設定'; ?>さん</p>
                			</div>
                			<div class="flexItem-Detail">
                    				<p class="workYear prof"><span>実務経験：</span><br><?php echo $userData[0]['workYear']? es($userData[0]['workYear']).'年' : '0年' ?></p>
                    				<p class="language prof"><span>得意な言語：</span><br><?php echo $userData[0]['language']? es($userData[0]['language']) : '未設定' ?></p>
                    				<p class="comment prof"><span>コメント：</span><br><?php echo $userData[0]['comment']? es($userData[0]['comment']) : '未設定'?></p>
                			</div>
            			</div>
        		</div>
  			<h2 class="goodTitle">お気に入りした本</h2>
     			<?php if(!empty($goodData)):?>
        			<div class="goodBookFlexContainer">
            				<?php foreach($goodData as $goodDatum) : ?>
            					<div class="goodBookFlexItem">
                					<p><?php echo es($goodDatum['bookTitle']); ?></p>
                					<a href="./bookDetail.php?bookNo=<?php echo es($goodDatum['no']); ?>">
                     						<img src="<?php echo (substr($goodDatum['imageUrl'],0,1) == 'h')? es($goodDatum['imageUrl']) : '../images/'.es($goodDatum['imageUrl']); ?>" alt="本の画像"><br>
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
                    						<img src="<?php echo (substr($reviewDatum['imageUrl'],0,1) == 'h')? es($reviewDatum['imageUrl'] ): '../images/'.es($reviewDatum['imageUrl']); ?>" alt="本の画像">
                					</a>
            					</div>
            					<div class="reviewItem">
                					<div class="textarea"><?php echo es($reviewDatum['reviewContent']); ?></div>
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
    		</main>

		<footer>
        		<p><small>&copy; 2019 TAICHI NAKANO</small></p>
    		</footer>
    
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    		<script src="../js/layout.js"></script>
	</body>
</html>

