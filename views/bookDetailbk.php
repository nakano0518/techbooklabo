
<?php
	session_start();
    	session_regenerate_id(true);
     
    	require_once '../lib/util.php';
    
    	if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
        	$userId = $_SESSION['userId'];
    	}
    	if(isset($_SESSION['reviewContent']) && !empty($_SESSION['reviewContent'])) {
        	$reviewContent = $_SESSION['reviewContent'];
    	}
    	if(isset($_GET)&& !empty($_GET)){
        	$bookNo = es($_GET['bookNo']);
    	}
    
/* -------------------------------------------------------------------------
本の情報をt_bookから取得する部分
--------------------------------------------------------------------------*/
    	try {
    		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
    		$db->createPdo();
        	$sql = "SELECT * FROM t_book WHERE no = :bookNo";
		$data = $db->selectfetch($sql, [[":bookNo", $bookNo, PDO::PARAM_STR]]);
        	if(!$data) {
           		throw new Exception('データベースエラー');
        	}
		//本の各種情報を変数に格納
        	$category = es($data['category']);
        	$title = es($data['bookTitle']);
        	$imageUrl = es($data['imageUrl']);
        	$affiliateUrl = es($data['affiliateUrl']);
        	$price = es($data['price']);
        	$pages = es($data['pages']);
        	$no = es($data['no']);
        	$description = es($data['description']);
	}catch(Exception $e) {
        	echo '<span class="error">エラーがありました。</span><br>';
        	echo $e->getMessage().'<br>';
    	}
?>
<?php
/* -------------------------------------------------------------------------
ログイン後、お気に入りボタンの着色の有無
------------------------------------------------------------------------- */
	if((isset($userId) && !empty($userId)) && (isset($bookNo) && !empty($bookNo))){
		try{
    			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
    			$db->createPdo();
        		$sql = "SELECT delete_flg FROM t_good WHERE userId = :userId AND no = :bookNo";
			$firstStarState = $db->selectfetch($sql, [[":userId", $userId, PDO::PARAM_STR], [":bookNo", $bookNo, PDO::PARAM_STR]]);
    		}catch(Exception $e){
        		echo '<span class="error">エラーがありました。</span><br>';
        		echo $e->getMessage();
    		}
	}
?>
<?php
/* -------------------------------------------------------------------------
お気に入りされた総数の取得
------------------------------------------------------------------------- */
	if(isset($bookNo) && !empty($bookNo)){
		try{
    			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
    			$db->createPdo();
    			$sql = "SELECT COUNT(*) AS count FROM t_good WHERE  no = :bookNo";
			$firstStarCount = $db->selectfetchAll($sql, [[":bookNo", $bookNo, PDO::PARAM_STR]]);
		}catch(Exception $e){
    			echo $e->getMessage();
		}
	}
?>
<?php
/* -------------------------------------------------------------------------
    投稿されたレビューをデータベースから取り出す。
--------------------------------------------------------------------------*/
    	if(isset($_GET['bookNo']) && !empty($_GET['bookNo'])) {//ログイン状態でなくてもレビューは見られるようにuserIdで制限はしない
        	// DBから本の詳細データを取得
        	try{
    			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
    			$db->createPdo();
    			$sql = 'SELECT t_users.imageUrl, t_users.name, t_users.workYear, t_users.language, t_users.followId, t_review.reviewContent FROM t_users INNER JOIN t_review ON t_users.userId = t_review.userId WHERE t_review.no = :bookNo'; 
			$reviewDatas = $db->selectfetchAll($sql, [[":bookNo", $bookNo, PDO::PARAM_STR]]);
	    	}catch(Exception $e){
			echo '<span class="error">エラーがありました。</span><br>';
            		echo $e->getMessage().'<br>';
	    	}
    	}

?>
<?php
/*----------------------------------------------------------------------
    ドロワーメニューに表示するユーザー名の取得 
----------------------------------------------------------------------*/
	if(isset($_SESSION['userId'])||!empty($_SESSION['userId'])){
    		try{
    			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
    			$db->createPdo();
        		$sql = "SELECT name FROM t_users WHERE userId = :userId";
			$name = $db->selectfetch($sql, [[":userId", $userId, PDO::PARAM_STR]]);
    		}catch(Exception $e){
			echo '<span class="error">エラーがありました。</span><br>';
        		echo $e->getMessage().'<br>';
    		}
	}
?>
<?php
/* ----------------------------------------------------------------------
S3から画像の読み取りのためのs3インスタンスの生成
-----------------------------------------------------------------------*/
	require_once '../vendor/autoload.php';
	$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
	$dotenv->load();
	$s3 = new Aws\S3\S3Client(array(
		'version' => 'latest',
		'credential' => array(
			'key' => getenv('AWS_BUCKET'),
			'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
		),
		'region' => getenv('AWS_DEFAULT_REGION'),
	));
?>





<!DOCTYPE html>
<html lang="ja">
	<head>
    		<meta charset="UTF-8">
    		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">    
		<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Sawarabi+Gothic&display=swap" rel="stylesheet">
    		<link rel="stylesheet" href="../css/bookDetail.css">
    		<title>TechBookLabo | 書籍詳細ページ</title>
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
                    <li class="<?= $userId ? 'invisible': ''; ?>">
                        <a href="./signIn.php" class="<?= $userId ? 'invisible': ''; ?>">ログイン</a>
                    </li>
                    <li class="<?= $userId ? 'visible': 'invisible'; ?>">
                        <a href="./mypage.php" class="<?= $userId ? 'visible': 'invisible'; ?>">マイページ</a>
                    </li>
                    <li class="<?= $userId ? 'visible': 'invisible'; ?>">
                        <a href="./signOut.php" class="<?= $userId ? 'visible': 'invisible'; ?>">ログアウト</a>
                    </li>
                </ul>
            </div>
        </div>
        </section>
    </header>
    <main>
        <section class="bookDetail">
            <h2 class="titleSection">
                <p class="category"><?= $category; ?></span><br>
                <p class="title"><?= $title; ?></span>
            </h2>
            <section class="post <?php $login === null? 'del': ''; ?>" data-id="<?php echo es($userId); ?>" data-no="<?php echo es($no); ?>">
		    <?php if(isset($userId) && !empty($userId)): ?>
                    <!-- 円 -->
                    <svg width="60" height="60" viewBox="0 0 350 60">
                        <circle cx="150" cy="40" r="100" stroke = "black" stroke-width="12" fill="white"></circle>
                        <!-- cx=中心のx座標 cy=中心のy座標 r=半径 -->
                    </svg>
                    <i id="star" class="fa-star <?php echo es($firstStarState['delete_flg']) == 1 ? "fas" : "far" ?>"></i>
                    <span class="starCount"><?php echo es($firstStarCount[0]['count']); ?></span>
		    <?php else: ?>	
                    <!-- 円 -->
                    <svg visibility="hidden" width="60" height="60" viewBox="0 0 350 60">
                        <circle cx="150" cy="40" r="100" stroke = "black" stroke-width="12" fill="white"></circle>
                        <!-- cx=中心のx座標 cy=中心のy座標 r=半径 -->
                    </svg>
                    <i class="fa-star fas"></i>
                    <span class="starCount"><?php echo es($firstStarCount[0]['count']); ?></span>
		    <?php endif; ?>
	    </section>
            <a href="<?php echo $affiliateUrl;?>"><img class="book" src="<?php echo (substr($imageUrl,0,1) == 'h')? $imageUrl : '../images/'.$imageUrl; ?>" alt="本の画像"></a><br>
            <p class="description"><?php echo $description; ?><span class="no">(ISBN-13：<?php echo $no; ?>)</span></p>
            <p class="price"><span><?php echo $price == 0? 'Unknown price':'￥'.number_format($price); ?></span></p>
	    <p class="pages"><span>/<?php echo $pages==0? 'Unknown ':$pages; ?>pages</span></p>
        </section>
        <section class="reviewSection">
	<?php if(isset($userId) && !empty($userId)):?>
            <div class="reviewInput">
                <h2>レビュー入力欄</h2>
                <form action="./reviewInput.php" method="post" class="<?php echo $userId ? '': 'invisible'; ?>">
                    <input type="hidden" name="bookNo" value="<?php echo es($bookNo); ?>">
                    <textarea name="reviewContent" placeholder='<?php echo "・初学者向けか、実務寄りの内容か&#13;&#10;・わかりやすさ、網羅性&#13;&#10;・その他気づいた事"; ?>' value="utf-8"><?php echo es($reviewContent); ?></textarea><br>
                    <input type="submit" value="投稿する">
                </form>
            </div>
	<?php endif;?>
            <div class="reviewList">
                <h2>レビュー一覧</h2>
                <?php if(!empty($reviewDatas)):?>
                <?php foreach($reviewDatas as $reviewData) : ?>
                <div class="reviewFlexContainer">
                    <div class="userInfo">
                        <?php
                            $reviewDataImageUrl = es($reviewData['imageUrl']);
			    $followId = es($reviewData['followId']);
			?>
			<?php
			/* ---------------------------------------------------------
			s3インスタンスから画像の取出し
			----------------------------------------------------------*/
			$getObj = $s3->getObject(array(
				'Bucket' => getenv('AWS_BUCKET'),
				'Key' => 'profileImages/'.basename($reviewDataImageUrl),
			));	
			$image = $getObj['@metadata']['effectiveUri'];
			?>
                        <a href="<?php echo './lookMyPage.php?followId='.$followId; ?>"><img src="<?php echo $reviewDataImageUrl === "" ?  '../images/userphoto/userDefault.jpg' : $image ; ?>" alt="レビューしたユーザーの写真"></a>
                        <p><?php echo es($reviewData['name']) ; ?>さん</p>
                        <p>実務経験年数：<?php echo es($reviewData['workYear']); ?>年</p>
                        <p>得意な言語：<?php echo es($reviewData['language']); ?></p>
                    </div>
                    <div class="reviewContent">
                        <p><?php echo es($reviewData['reviewContent']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <p class="noReview">まだレビューはありません</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        <p><small>&copy; 2019 TAICHI NAKANO</small></p>
    </footer>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    <script src="../js/layout.js"></script>
    <script src="../js/bookDetail.js"></script>
</body>
</html>
