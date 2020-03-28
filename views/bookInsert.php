<?php
    	session_start();
    	session_regenerate_id(true);
    	require_once '../lib/util.php';
    	require_once '../lib/config.php';
    	require_once '../lib/whiteList.php';
	require_once '../vendor/autoload.php';
	$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
	$dotenv->load();

    	if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])){
        	$userId = $_SESSION['userId'];
    	}else {
   		$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		if($_SERVER['HTTPS'] !== null){
			$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		}
		header('Location:'.$goBackURL.'/signIn.php');
		exit;
    	}

    	$bookKeywords = "";
	if(isset($_GET['bookKeywords'])){
        	$bookKeywords = es($_GET['bookKeywords']);
    	}




	//get送信されたキーワードに含まれる全角空白を半角空白にする
	$bookKeywords = mb_convert_kana($bookKeywords, "s");
	//大文字を小文字にする
	$bookKeywords = mb_strtolower($bookKeywords);
	//半角空白とカンマで分割し配列化する
	$bookKeywords = preg_split("/[\s,]+/",$bookKeywords, -1, PREG_SPLIT_NO_EMPTY); 

	$flag = false;
	$filterKeywords = array();

	for($i=0; $i<count($bookKeywords); $i++) {
    		for($j=0; $j<count($keywords);$j++){
        		if($bookKeywords[$i] === $keywords[$j]){
            			$filterKeywords[] = $keywords[$j];
            			$flag = true;
        		} 
    		}	
	}

	//$filterKeywordが複数あるときクエリ文字列に複数列挙できるよう＋で連結。
	$valuePlusConnect = "";
	foreach ($filterKeywords as $value) {
    		$valuePlusConnect .= $value.'+';
	}
	$valuePlusConnect = substr($valuePlusConnect, 0, -1);



	if($flag){
    
    		//現在いるページ番号の取得
    		if(!isset($_GET['page_id'])){
        		$now = 1;
    		}else{
		        $now = $_GET['page_id'];
    		}
		/* ------------------------------------------------------------------------------------------------
		楽天ブックスにアクセス。
		------------------------------------------------------------------------------------------------- */    
		$params = array();
		$params['format']              = 'json';
		$params['applicationId']       = getenv("APPLICATION_ID");
		$params['application_secret']  = getenv("APPLICATION_SECRET");
		$params['affiliateId']         = getenv("AFFILIATE_ID"); //アフィリエイトの際に必要
		$params['title']               = implode(' ', $filterKeywords);
		$params['hits']                = 12;//1ページ当たりの件数
		$params['page']                = $now;//何ページ目からか

		$requestURL = getenv("ACCESS_URL");
		foreach($params as $key => $param){
    			$requestURL .= "&{$key}={$param}";
		}

		//結果がjson形式で帰ってくるのでdecodeし配列へ
		$request = file_get_contents($requestURL);
		$info    = json_decode($request, true);

		// 全体の件数を取得
		$total_count = $info['count'];
		
		// 書籍情報を取得
		$books = $info['Items'];
		
		// 実際に取得した件数
		$get_count = count($books);
		
		//ページ数の算出
		$pages = ceil( $total_count / $params['hits']);
	}else{
    		//取得した件数
		$get_count = 0;
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
			$name = $db->selectfetch($sql,[[':userId', $userId, PDO::PARAM_STR]]);
    		}catch(Exception $e){
        		echo $e->getMessage();
    		}
	}
?>




<!DOCTYPE html>
<html lang="ja">
	<head>
    		<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Sawarabi+Gothic&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
  		<link rel="stylesheet" href="../css/bookInsert.css">
  		<meta charset="UTF-8">
 		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>TechBookLabo | 書籍追加ページ</title>
	</head>
	<body>
    		<!-- モバイル用ドロワーメニューここから -->
        	<div class="drawer">
            		<div class="drawerMenu">
                		<?php if(isset($userId) && !empty($userId)): ?>
                    			<div class="signInMenu">
                       	 			<p>ようこそ「<?php echo isset($name['name']) && !empty($name['name']) ? $name['name'] : "名称未設定" ; ?>」さん</p>
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
                        					<a href="./signOut.php" class="<?php echo $userId ? 'visible': 'invisible'; ?>">ログアウト</a>
                    					</li>
               	 				</ul>
           	 			</div>
        			</div>
        		</section>
    		</header>

		<main>
  			<form action="./bookInsert.php" method="get">
        			<input type="text" name="bookKeywords" placeholder="技術キーワードを入力してください" value="<?php echo es($_GET['bookKeywords']); ?>">
        			<button type="submit"><i class="fas fa-search"></i></button>
  			</form>
  			<?php if((isset($total_count)&&!empty($total_count)) && (isset($get_count)&&!empty($get_count))): ?>
  				<p class="count">全<?php echo $total_count; ?>件中、<?php echo $get_count; ?>件</p>
  			<?php endif; ?>
  			<p class="btnDescription"><span><i class="fas fa-plus"></i></span> ボタンで書籍を追加してください。</p>
  			<?php if($get_count > 0): ?>
    				<div class="loop_books">
      					<?php foreach($books as $book):
          					// タイトル
          					$title = es($book['Item']['title']);
         					// サムネ画像
          					$imgURL = es($book['Item']['largeImageUrl']);
          					//ISBN13
          					$no = es($book['Item']['isbn']);
          					//アフィリエイトURL
          					$affiliateUrl = es($book['Item']['affiliateUrl']);
      					?>
        					<div class="loop_books_item" data-no="<?php echo es($no); ?>" data-id="<?php echo es($userId); ?>">
          						<i class="fas fa-plus"></i><br>
          						<a href="<?php echo $affiliateUrl; ?>">
          						<img src="<?php echo $imgURL; ?>" alt="<?php echo $title; ?>"></a><br />
          						<h1><?php echo $title; ?></h1>
        					</div>
      					<?php endforeach; ?>
    				</div>
  			<?php else: ?>
    				<p class="noInfo">情報がありません</p>
  			<?php endif; ?>	
			<!-- ページネーションここから -->	
			<section class="page">
        			<?php
					if(isset($pages)&&!empty($pages)) {
        					if($now == 1){
        						echo "<span style='padding:5px;font-size:18px;margin-right:5px;'>{$now}</span>";
						}else{	
                					echo "<a href='./bookInsert.php?bookKeywords=$valuePlusConnect&page_id=1'
                					style='padding:5px 10px;font-size:18px;border:1px solid #fff;text-decoration:none;color:#fff;margin-right:5px;'>1</a>";
						}
						
						if($now >= 4){
							echo "・・・";
						}
						
						for($n=2; $n<=$pages-1;$n++){
                					if($n==$now){
                    						echo "<span style='padding:5px;font-size:18px;margin-right:5px;'>{$now}</span>";
                					}else if($n == $now-1 || $n == $now+1){
                    						echo "<a href='./bookInsert.php?bookKeywords=$valuePlusConnect&page_id=$n' style='padding:5px 10px;font-size:18px;border:1px solid #fff;text-decoration:none;color:#fff;margin-right:5px;'>{$n}</a>";
                					}else{
								continue;
							}
            					}
						
						if($now < $pages-2){
							echo "・・・";
						}
					
						if($pages == 1){
							//何もしない
						}else if($now == $pages){
                    					echo "<span style='padding:5px;font-size:18px;margin-right:5px;'>{$pages}</span>";
						}else{
                    					echo "<a href='./bookInsert.php?bookKeywords=$valuePlusConnect&page_id=$pages' style='padding:5px 10px;font-size:18px;border:1px solid #fff;text-decoration:none;color:#fff;margin-right:5px;'>{$pages}</a>";
						}
        				}
        			?>
    			</section>
			<!-- ページネーションここまで -->
  		</main>
		
		<footer>
        		<p><small>&copy; 2019 TAICHI NAKANO</small></p>
    		</footer>
  
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
  		<script src="../js/bookInsert.js"></script>
  		<script src="../js/layout.js"></script>
	</body>
</html>
