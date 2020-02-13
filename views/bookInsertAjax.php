<?php
session_start();
require_once "../lib/util.php";
require_once "../lib/config.php";
require_once "../lib/whiteList.php";

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
$params['applicationId']       = APPLICATION_ID;
$params['application_seacret'] = APPLICATION_SEACRET;
$params['affiliateId']         = AFFILIATE_ID; //アフィリエイトの際に必要
$params['isbn']                = $bookNo;

$requestURL = ACCESS_URL;
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
        $title        = $Item['title'];
        // DBで管理しやすいように文字コードの宣言やスペースの削除等を行う
        $bookTitle        = mb_convert_kana($title, "as", "UTF-8");
         
        //本の表紙の大サイズのURL(サイズは小中大から選べる)
        //$imageUrl       = $Item['largeImageUrl']; 
        $imageUrl       = $Item['mediumImageUrl']; //本の表紙の中サイズのURL(サイズは小中大から選べる)
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

var_dump($data);
// 書籍情報を取得
$books = $data->items;
var_dump($books);
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
    $user = 'nakano';
    $dbpassword = '3114yashi';
    $dbName = 'BookReview';
    $host = 'techbookreview.ccbw4hq0h1r9.ap-northeast-1.rds.amazonaws.com';
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";
    try {
        $pdo = new PDO($dsn, $user, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
        $sql = "INSERT INTO t_book(no, bookTitle, imageUrl, affiliateUrl, category, price, pages, description, register, modified_at)
    VALUES(:no, :bookTitle, :imageUrl, :affiliateUrl, :category, :price, :pages, :description, :register, :modified_at)";
        $insert = $pdo->prepare($sql);
        $insert->bindValue(':no', $bookNo, PDO::PARAM_STR);
        $insert->bindValue(':bookTitle', $bookTitle, PDO::PARAM_STR);
        $insert->bindValue(':imageUrl', $imageUrl, PDO::PARAM_STR);
        $insert->bindValue(':affiliateUrl', $affiliateUrl, PDO::PARAM_STR);
        $insert->bindValue(':category', $category, PDO::PARAM_STR);
        $insert->bindValue(':price', $price, PDO::PARAM_INT);
        $insert->bindValue(':pages', $pages, PDO::PARAM_INT);
        $insert->bindValue(':description', $description, PDO::PARAM_STR);
        $insert->bindValue(':register', $userId, PDO::PARAM_STR);
        $insert->bindValue(':modified_at', date(), PDO::PARAM_STR);
        $insert->execute();

    }catch(Exception $e){
        echo $e->getMessage();
    }
?>

