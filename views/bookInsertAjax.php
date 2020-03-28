<?php
	session_start();
	require_once "../lib/util.php";
	require_once "../lib/whiteList.php";

	require_once "../vendor/autoload.php";
	$dotenv = Dotenv\Dotenv::createImmutable("../../env/");
	$dotenv->load();

 	if(isset($_POST) && !empty($_POST)){
        	$bookNo = es($_POST['bookNo']);
        	$userId = es($_POST['userId']);
    	}

?>
<?php
/*------------------------------------------------------------------------------------------------------------------
    楽天ブックスAPIから「ページ数」以外の情報を取得する
    ⇒「ページ数」は取得できないのでGoogleBooksAPIからISBNを用いて取得する。
-------------------------------------------------------------------------------------------------------------------*/

	$params = array();
	$params['format']              = 'json';
	$params['applicationId']       = getenv("APPLICATION_ID");
	$params['application_secret'] = getenv("APPLICATION_SECRET");
	$params['affiliateId']         = getenv("AFFILIATE_ID"); //アフィリエイトの際に必要
	$params['isbn']                = $bookNo;

	$requestURL = getenv("ACCESS_URL");
		foreach($params as $key => $param){
    			$requestURL .= "&{$key}={$param}";
	}

	//結果がjson形式で帰ってくるのでdecodeし配列へ
	$request = file_get_contents($requestURL);
	$info    = json_decode($request, true);

	foreach ($info as $key => $Items) {
    		if ($key == "Items") {
        		$Item = $Items['0']['Item'];
        		
			// 各情報を配列へ格納
        		//タイトル
        		$title = $Item['title'];
        		
			// DBで管理しやすいように文字コードの宣言やスペースの削除等を行う
        		$bookTitle = mb_convert_kana($title, "as", "UTF-8");
         
        		//本の表紙の大サイズのURL(サイズは小中大から選べる)
        		//$imageUrl = $Item['largeImageUrl']; 
        		$imageUrl = $Item['mediumImageUrl']; //本の表紙の中サイズのURL(サイズは小中大から選べる)
        		$imageUrl = str_replace('?_ex=120x120', '', $imageUrl);//元の画像のサイズ(高解像度)に加工する
        
        		//アフィリエイト用商品URL
        		$affiliateUrl = $Item['affiliateUrl'];
 
        		//priceの取得
        		$price = $Item['itemPrice'];
        
        		//descriptionの取得
        		$description = $Item['itemCaption'];
        
        		//categoryの取得
           		//(whiteList.phpから技術キーワード格納配列$keywordsを読込済み)
        		$categories = array();//空の配列用意
        		for($i=0; $i < count($keywords); $i++) {
            			if(preg_match("{".$keywords[$i]."}i", $bookTitle) === 1 || preg_match("{".$keywords[$i]."}i", $description) === 1){ 
                		//$bookTitleや$descriptionに技術キーワードがマッチすれば、格納する。
                		$categories[] = $keywords[$i];
            		}
        	}
        	$category = implode(',', $categories);//,で連結して文字列として$categoryに格納 
    	}
}

/*------------------------------------------------------------------------------
    GoogleBooksAPIから「ページ数」の情報を取得する
-------------------------------------------------------------------------------*/
	// GoogleBooksAPIにISBN-13を指定してリクエストするためのURL
	$url = "https://www.googleapis.com/books/v1/volumes?q=isbn:{$bookNo}";

	// 書籍情報を取得
	$json = file_get_contents($url);

	//文字化けしないように
	$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

	// デコード（objectに変換）
	$data = json_decode($json);

	// 書籍情報を取得
	$books = $data->items;

	foreach($books as $book){
    		//pagesの取得
    		$pages = $book->volumeInfo->pageCount;
	}

	if(is_null($pages)){
    		//INT型でデータベースに格納する必要性があるので、不明=0とする。
    		$pages = 0;
	}

/*------------------------------------------------------------------------------
    取得したデータをt_bookテーブルにINSERTする
-------------------------------------------------------------------------------*/
	try {
		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
        	$sql = "INSERT INTO t_book(no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, modified_at)
    VALUES(:no, :bookTitle, :imageUrl, :affiliateUrl, :category, :price, :pages, :description, :register, :modified_at)";
        	$db->dml($sql, [
        		[':no', $bookNo, PDO::PARAM_STR],
        		[':bookTitle', $bookTitle, PDO::PARAM_STR],
        		[':imageUrl', $imageUrl, PDO::PARAM_STR],
        		[':affiliateUrl', $affiliateUrl, PDO::PARAM_STR],
        		[':category', $category, PDO::PARAM_STR],
        		[':price', $price, PDO::PARAM_INT],
        		[':pages', $pages, PDO::PARAM_INT],
        		[':description', $description, PDO::PARAM_STR],
        		[':register', $userId, PDO::PARAM_STR],
        		[':modified_at', date(), PDO::PARAM_STR]
		]);

    	}catch(Exception $e){
        	echo $e->getMessage();
    	}
?>

