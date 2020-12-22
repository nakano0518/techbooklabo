<?php
	session_start();
	session_regenerate_id(true);


	require_once __DIR__.'/config/envLoad.php';
	require_once __DIR__.'/lib/util.php';	
	
	
	
	if(isset($_SESSION['userId'])) {
		$userId = $_SESSION['userId'];
	}	

// 	try{
// 		/*-------------------------------------------------------------------------------------------------------------
// 		キーワード検索(SQL文のWHERE句を作成)
// 		-------------------------------------------------------------------------------------------------------------*/
// 		$where = "";//SQL文のWHERE句
// 		$valuePlusConnect = '';//複数キーワードを+で結合した値を格納
// 		$querySelectInput = '';//作成したクエリ文字列
// 		if(isset($_GET['searchInput'])) {
//         		//入力値を半角(全角)空白とカンマで分割し、検索キーワード化
//         		$searchInput = $_GET['searchInput'];
//         		$searchInput = mb_convert_kana($searchInput, "s");//全角空白を半角空白に変換
//         		$searchInput = preg_split("/[\s,]+/",$searchInput, -1, PREG_SPLIT_NO_EMPTY); //半角空白と,で分割し配列化
//         		$keywordsCount = count($searchInput);//配列数、つまり検索キーワード数
        
//         		//複数キーワードを+で結合(クエリ文字列作成のため)
//         		foreach ($searchInput as $value) {
//             			$valuePlusConnect .= $value.'+';
//         		}
//       			$valuePlusConnect = substr($valuePlusConnect, 0, -1);//末尾の+削除
        
//         		//上記をクエリ文字列の形式に変形
//         		if(isset($_GET['searchInput'])){
//             			$querySelectInput = 'searchInput='.$valuePlusConnect.'&';
//         		}else{
//             			$querySelectInput = '';
//         		}	
        
        
//         		//検索キーワードをforで展開しWHERE句を作成
//         		if(!empty($_GET['searchInput'])){
// 				$where = ' WHERE';
//         			for($i=0; $i<$keywordsCount; $i++) {
//             				$searchInput[$i] = es($searchInput[$i]);!
//             				$where .= ' bookTitle like'.'\''.'%'.$searchInput[$i].'%'.'\''.'OR';
//         			}
//         			for($j=0; $j<$keywordsCount; $j++) {
//             				$searchInput[$j] = es($searchInput[$j]);
//             				$where .= ' category like'.'\''.'%'.$searchInput[$j].'%'.'\'';
//             				if($j<$keywordsCount-1) {
//                 				$where .= 'OR';
//             				}
//         			}
// 			}
        
//     		}
		
// 		/*----------------------------------------------------------------------
// 		並べ替え(SQL文のORDER BY句の作成)
// 		----------------------------------------------------------------------*/
// 		$counter = '';//COUNT()の設定
// 		$join = '';//テーブル結合句
//     		$orderBy = ' ORDER BY t_book.created_at DESC';//ORDER BY句
//     		if(isset($_GET['sort'])) {
//             		$sort = es($_GET['sort']);
//             		switch($sort) {
//                 		case 1: 
//                     			$orderBy = ' ORDER BY price ASC';//価格昇順
//                     			break;
//                 		case 2:
//                     			$orderBy = ' ORDER BY price DESC';//価格降順
//                     			break;
//                 		case 3:
//                     			$orderBy = ' ORDER BY pages ASC';//ページ昇順
//                     			break;
//                 		case 4:
//                     			$orderBy = ' ORDER BY pages DESC';//ページ降順
// 					break;
// 				case 5:
// 					$counter = ', COUNT(t_good.no) AS kensu';
// 					$join = ' LEFT OUTER JOIN t_good ON t_good.no = t_book.no';
// 					$orderBy = ' GROUP BY t_book.no ORDER BY kensu ASC, t_book.created_at DESC';//お気に入り数の昇順
// 					break;

// 				case 6:
// 					$counter = ', COUNT(t_good.no) AS kensu';
// 					$join = ' LEFT OUTER JOIN t_good ON t_good.no = t_book.no';
// 					$orderBy = ' GROUP BY t_book.no ORDER BY kensu DESC, t_book.created_at DESC';//お気に入り数の降順
// 					break;
// 				case 7:
// 					$counter = ', COUNT(t_review.no) AS kensu';
// 					$join = ' LEFT OUTER JOIN t_review ON t_review.no = t_book.no';
// 					$orderBy = ' GROUP BY t_book.no ORDER BY kensu ASC, t_book.created_at DESC';//レビュー数の昇順
// 					break;
// 				case 8:					
// 					$counter = ', COUNT(t_review.no) AS kensu';
// 					$join = ' LEFT OUTER JOIN t_review ON t_review.no = t_book.no';
// 					$orderBy = ' GROUP BY t_book.no ORDER BY kensu DESC, t_book.created_at DESC';//レビュー数の降順
// 					break;
// 				default:
// 					$orderBy = ' ORDER BY t_book.created_at DESC';
// 					break;
//             		}
//     		}
//     		$querySort = '';//クエリ文字列の作成
//     		if(isset($_GET['sort'])) {
//         		$querySort = 'sort='.$sort.'&';
//     		}else {
//         		$querySort = '';
//     		}
		
// 		/*----------------------------------------------------------------------
// 		ページネーション 
// 		----------------------------------------------------------------------*/
// 		//1ページあたり何冊表示するか
// 		define('max_item', 12);
// 		//必要なページ数を求める
// 		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
// 		$db->createPdo();
// 		$sql = 'SELECT COUNT(*) AS count FROM t_book'.$where;
// 		$total_count = $db->selectfetch($sql);	
// 		$pages = ceil($total_count['count'] / max_item);

// 		//現在いるページ番号の取得
// 		$now = 0;
// 		if(!isset($_GET['page_id'])||empty($_GET['page_id'])){
//     			$now = 1;
// 		}else{
//     			$now = es($_GET['page_id']);
// 		}

// 		//表示する本のコンテンツを取得するSQL
// 		$sql = 'SELECT t_book.bookTitle, t_book.imageUrl, t_book.no, t_book.created_at'.$counter.' FROM t_book'.$join.$where.$orderBy.' LIMIT :start, :max';
		
// 		if($now ==1){
//   			//1ページ目の処理
// 			$data = $db->selectfetchAll($sql,[
//     				[':start', $now-1, PDO::PARAM_INT],
//     				[':max', max_item, PDO::PARAM_INT]
// 			]);
// 		}else{
//     			//1ページ目以外の処理	
// 			$data = $db->selectfetchAll($sql,[
//     				[':start', ($now-1) * max_item, PDO::PARAM_INT],
//     				[':max', max_item, PDO::PARAM_INT]
// 			]);
// 		}
// 	}catch(Exception $e) {
// 		echo '<span class="error">エラーがありました。</span><br>';
//         	echo $e->getMessage().'<br>';
// 	}
// ?>

// <?php
// 	/*----------------------------------------------------------------------
//     	モバイル用ドロワーメニューに表示するユーザー名の取得 
// 	----------------------------------------------------------------------*/
// 	if(isset($_SESSION['userId'])||!empty($_SESSION['userId'])){
//     		try{
// 			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
//     			$db->createPdo();
//         		$sql = "SELECT name FROM t_users WHERE userId = :userId";
//         		$name = $db->selectfetch($sql, [[":userId", $userId, PDO::PARAM_STR]]);
//   		}catch(Exception $e){
// 			echo '<span class="error">エラーがありました。</span><br>';
//         		echo $e->getMessage().'<br>';
//     		}
// 	}
	
// ?>



<!DOCTYPE html>
<html lang="ja">
	<head>
    		<meta charset="UTF-8">
    		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">    
    		<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Sawarabi+Gothic&display=swap" rel="stylesheet">
    		<link rel="stylesheet" href="./css/index.css">
    		<title>TechBookLabo | エンジニアのための書籍レビューサイト</title>
	</head>
	
	<body>
    		<!-- モバイル用ドロワーメニューここから -->
        	<div class="drawer">
            		<div class="drawerMenu">
                		<?php if(isset($userId) && !empty($userId)): ?>
                    			<div class="signInMenu">
                        			<p>ようこそ「<?php echo isset($name['name']) && !empty($name['name']) ? $name['name'] : "名称未設定" ; ?>」さん</p>
                        			<ul>
			    				<li><a href="./index.php">ホーム</a></li>
                            				<li><a href="./views/mypage.php">マイページ</a></li>
                            				<li><a href="./views/signOut.php">ログアウト</a></li>
                        			</ul>
                    			</div>
                		<?php else: ?>
                    			<div class="signOutMenu">
                        			<p>ログイン or 新規登録</p>
                        			<ul>
			    				<li><a href="./index.php">ホーム</a></li>
                           		 		<li><a href="./views/signUp.php">新規登録</a></li>
                            				<li><a href="./views/signIn.php">ログイン</a></li>
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
        			<img src="./images/booklogo.svg">
        			<div class="headercenter">
            				<a href="./index.php">
                				<span class="title">TechBookLabo</span>
            				</a>
        			</div>
        			<div class="headerright">
            				<i class="fas fa-bars"></i>
            				<div class="menus">
                				<ul>
                    					<li><a href="./views/signUp.php">新規登録</a></li>
                    					<li class="<?php echo isset($userId)||!empty($userId) ? 'invisible': ''; ?>">
                        					<a href="./views/signIn.php" class="<?php echo $userId ? 'invisible': ''; ?>">ログイン</a>
                    					</li>
                    					<li class="<?php echo isset($userId)||!empty($userId) ? 'visible': 'invisible'; ?>">
                        					<a href="./views/mypage.php" class="<?php echo isset($userId)||!empty($userId) ? 'visible': 'invisible'; ?>">マイページ</a>
                    					</li>
                    					<li class="<?php echo isset($userId)||!empty($userId) ? 'visible': 'invisible'; ?>">
                        					<a href="./views/signOut.php" class="<?php echo isset($userId)||!empty($userId) ? 'visible': 'invisible'; ?>">ログアウト</a>
                    					</li>
                				</ul>
            				</div>
        			</div>
        		</section>
    		</header>
    
		<main>
        		<section class="controlSection">
            			<div class="component">
                			<form action='<?php echo "./index.php?".$querySelectInput.$querySort."&page_id=".$n ; ?>' method="get">
                    				<div id="searchForm">
                        				<input type="text" name="searchInput" placeholder="書籍名やジャンルを入力" value="<?php echo empty($_GET['searchInput'])? '': es($_GET['searchInput']); ?>">
                        				<button type="submit"><i class="fas fa-search"></i></button>
                    				</div>
                    				<button class="sort">
                        				<i class="fas fa-sort-down"></i>
                        				<span class="sortDescription">並べ替え</span>
                    				</button>
                    				<div class="sortForm tag">
                      					<button type="submit" name="sort" value="1">価格の安い順</button>
                      					<button type="submit" name="sort" value="2">価格の高い順</button>
                      					<button type="submit" name="sort" value="3">ページ数の少ない順</button>
                      					<button type="submit" name="sort" value="4">ページ数の多い順</button>
                      					<button type="submit" name="sort" value="5">お気に入り数の少ない順</button>
                      					<button type="submit" name="sort" value="6">お気に入り数のの多い順</button>
                      					<button type="submit" name="sort" value="7">レビュー数の少ない順</button>
                      					<button type="submit" name="sort" value="8">レビュー数の多い順</button>
		    				</div>

                			</form>	
                			<button class="bookInsert" onclick="location.href='./views/bookInsert.php'">
                    				<i class="fas fa-plus"></i>
                    				<span class="bookInsertDescription">書籍の登録</span>
                			</button>
            			</div>
        		</section>
    			<section class="flexContainer">
    				<?php
    					foreach($data as $row) {
        					$no = $row['no'];
    				?>
　　 					<div class="book">
       						<a href="./views/bookDetail.php?bookNo=<?= $no; ?>">
        						<img src="<?php echo $row['imageUrl']; ?>" width="150" height="180"><br>
       							<p class="capture"><?= $row['bookTitle']; ?></p>
       						</a>
     					</div>
    				<?php
    					}
    				?>
    				<div class="book"></div><div class="book"></div><div class="book"></div><div class="book"></div><div class="book"></div>
    			</section>
    			<!-- ページネーションここから -->
			<section class="page">
        			<?php
					if($now == 1){	
                				echo "<span style='padding:5px;font-size:18px;'>{$now}</span>";
					}else{
                				echo "<a href='./index.php?{$querySelectInput}{$querySort}page_id=1' style='padding:5px 10px;font-size:18px;border:1px solid #000;text-decoration:none;color:#000;'>1</a>";
					}
					if($now>=4){
						echo "・・・";
					}
        				for($n=2; $n<=$pages-1;$n++){
	    					if($n==$now){
                					echo "<span style='padding:5px;font-size:18px;'>{$now}</span>";
            					}else if($n == $now-1 || $n == $now+1){
                					echo "<a href='./index.php?{$querySelectInput}{$querySort}page_id=$n' style='padding:5px 10px;font-size:18px;border:1px solid #000;text-decoration:none;color:#000;'>{$n}</a>";
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
       		 				echo "<span style='padding:5px;font-size:18px;'>{$pages}</span>";
					}else{
        					echo "<a href='./index.php?{$querySelectInput}{$querySort}page_id=$pages' style='padding:5px 10px;font-size:18px;border:1px solid #000;text-decoration:none;color:#000;'>{$pages}</a>";
					}    
        			?>
    			</section>
    			<!-- ページネーションここまで -->
    		</main>
    
		<footer>
        		<p><small>&copy; 2019 TAICHI NAKANO</small></p>
    		</footer>
    
		<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
    		<script src="./js/index.js"></script>
    		<script src="./js/layout.js"></script>
	</body>
</html>
