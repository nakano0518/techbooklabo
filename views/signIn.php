<?php
	session_start();

	require_once '../lib/util.php';

	//CSRF対策：送信するトークン
	$token_byte = openssl_random_pseudo_bytes(16);//暗号的に安全でランダムなバイナリ
	$csrf_token = bin2hex($token_byte);//それを16進数に変換

?>




<!DOCTYPE html>
<html lang="ja">
	<head>
    		<meta charset="UTF-8">
    		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    		<meta http-equiv="X-UA-Compatible" content="ie=edge">  
    		<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Sawarabi+Gothic&display=swap" rel="stylesheet">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    		<link rel="stylesheet" href="../css/signIn.css">
    		<title>TechBookLabo</title>
	</head>

	<body>
        	<div class="signIn">
            		<a href="../index.php" class="goHome">
                		<div class="logo">
                    			<img src="../images/booklogo.svg">
                    			<span class="title">TechbookLabo</span>
                		</div>
            		</a>
            		<h1>ログイン</h1>
            		<p class="userIdPasswordError">
                		<?php echo $_SESSION['errors']['userIdEmpty']||$_SESSION['errors']['passwordEmpty']? '' : $_SESSION['errors']['userIdPasswordError']; ?>
            		</p>
            		<p class="dbError">
                		<?php echo  $_SESSION['errors']['userIdEmpty']||$_SESSION['errors']['passwordEmpty']? '' : $_SESSION['errors']['dbError']; ?>
            		</p>
            		<form action="./signInConfirm.php" method="post">
                		<label>
                    			<span class="userId">ユーザーID</span> 
                   	 		<input type="text" name="userId" placeholder="ユーザーID" value="<?php echo $_SESSION['userId']; ?>">
                		</label>
                		<p class="userIdEmpty"><?php echo $_SESSION['errors']['userIdEmpty']; ?></p>
                		<label>
                    			<span class="password">パスワード</span>
                    			<input type="password" name="password" placeholder="8文字以上半角英数(大文字含)">
                		</label><br>
                		<p class="passwordEmpty"><?php echo $_SESSION['errors']['passwordEmpty']; ?></p>
				<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                		<input type="submit" value="ログイン">
            		</form>
            		<p>あるいは</p>
	    		<a class="testLogin" href='./testLogin.php'>テストログイン</a> 
            		<div class="signUpForm">
                		<a href="./signUp.php">新規登録はこちら</a>
            		</div>
        	</div>
    
			<?php
				//エラー用セッションを空に
				$_SESSION['errors']['userIdEmpty'] = '';
				$_SESSION['errors']['passwordEmpty'] = '';
				$_SESSION['errors']['userIdPasswordError'] = '';
				$_SESSION['errors']['dbError'] = '';
				
	
				//CSRF対策:セッションにトークンを保存しておく
	    			$_SESSION['csrf_token'] = $csrf_token;
	    	?>
	</body>
</html>
