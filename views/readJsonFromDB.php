<?php
//AjaxでPOSTされたuserIdを受け取る。
if($_POST['userId']){
    $userId = $_POST['userId'];
}else {
    $userId = '';
}


//このPHPファイルの返り値を格納する変数
$result = '';

//あらかじめDBから取得したuserIdの情報が入ったJSONファイルのパス。
$jsonUrl = '../json/jsonfile/userId.json';


if(file_exists($jsonUrl)){
    $json = file_get_contents($jsonUrl);
    $json = mb_convert_encoding($json, 'UTF-8', 'ASCII, JIS, UTF-8, EUC-JP, SJIS-WIN');
    $obj =json_decode($json, true);
    for($i=0; $i<count($obj); $i++){
        if($userId === $obj[$i]['userId']){
            $result = $obj[$i]['userId'];
        }
    }
    echo $result;
}else{
    $result = 'データがありません。';//signUp.jsで「ユーザーIDは既に使用されています」に行くようにしておく。
    echo $result;
}
