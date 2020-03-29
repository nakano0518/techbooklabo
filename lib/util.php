<?php
	// ★XSS対策のためのHTMLエスケープ関数を定義
	function es($data, $charset='UTF-8'){
  		if (is_array($data)){//$dataが配列の時
    			// 再帰呼び出し
    			// __METHOD__はマジカル定数で現在実行している関数を呼び出す
    			// 今回であればes関数を呼び出す
    			// array_mapとの併用でどれだけ深い配列であってもhtmlspecialchars関数を
    			// データに対して実行できる
    			return array_map(__METHOD__, $data);
  		} else {//$dataが配列でないとき
  			// HTMLエスケープを行う
    			return htmlspecialchars($data, ENT_QUOTES, $charset);
  		}
	}

	// ★配列の文字エンコードのチェックを行う関数を定義
	function cken(array $data){
  		$result = true;
  		foreach ($data as $key => $value) {
    			if (is_array($value)){
      				// 含まれている値が配列のとき文字列に連結する
      				$value = implode("", $value);
    			}
    			// mb_check_encoding関数は引数の文字列の文字コードが
    			// サーバーの文字コードと一致したらtrue、一致しなければfalseを返す
    			if (!mb_check_encoding($value)){
      				// 文字エンコードが一致しないとき
      				$result = false;
      				// foreachでの走査をブレイクする
      				break;
    			}
  		}
  		return $result;
	}

	// ★セッションを破棄する関数を定義
	function killSession(){
  		// セッション変数の値を空にする
  			$_SESSION = array();
 		// セッションクッキーを破棄する
  			if (isset($_COOKIE[session_name()])){
    				$params = session_get_cookie_params();
    				setcookie(session_name(), '', time()-36000, $params['path']);
  			}
			session_destroy();//サーバー側のセッションを削除
	}
