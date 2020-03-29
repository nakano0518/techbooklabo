<?php
	session_start();
	session_regenerate_id(true);

	require_once '../lib/util.php';
	require_once '../lib/validation.php';

	if(isset($_SESSION['newUserId'])||!empty($_SESSION['newUserId'])){
    		$newUserId = $_SESSION['newUserId'];
	}

	if(isset($_SESSION['validationErrors'])||!empty($_SESSION['validationErrors'])){
    		$validationErrors = $_SESSION['validationErrors'];
	}
?>



<!DOCTYPE html>
<html lang="ja">
	<head>
    		<meta charset="UTF-8">
    		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    		<meta http-equiv="X-UA-Compatible" content="ie=edge">
    		<link href="https://fonts.googleapis.com/css?family=Roboto+Mono|Sawarabi+Gothic&display=swap" rel="stylesheet">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    		<link rel="stylesheet" href="../css/signUp.css">
    		<title>TechBookLabo | 新規登録</title>
	</head>

	<body>
        	<div class="signIn">
            		<div class="logo">
                		<a href="../index.php">
                    			<img src="../images/booklogo.svg">
                    			<span class="title">TechBookLabo</span>
                		</a>
            		</div>
            		<h1>新規登録</h1>
            		<form action="./signUpConfirm.php" method="post">
            			<label>
                    			<span class="userId">ユーザーID</span> 
                    			<input type="text" name="newUserId" placeholder="ユーザーID" value="<?php echo isset($newUserId)||!empty($newUserId)? $newUserId: ""; ?>"><br>
                		</label>
				<span class="userIdError_first">
                        		<?php if(isset($newUserId)||!empty($newUserId)): ?>    
                            			<?php if(isDuplicateUserId($newUserId) === 0): ?>
                                			<span class="firstEmpty"></span>
                            			<?php elseif(isDuplicateUserId($newUserId)) : ?>
                                			<span class="firstNG">× ユーザーIDは既に使用されています。</span>
                            			<?php else: ?>
                                			<span class="firstOK">〇 ユーザーIDは使用できます。</span>
                            			<?php endif; ?>
                        		<?php endif;?>
                		</span>
				<span class='checkInputEmpty_newUserId'>
                    			<?php 
                        			echo isset($validationErrors['checkInputEmpty_newUserId'])||!empty($validationErrors['checkInputEmpty_newUserId'])?
                        			$validationErrors['checkInputEmpty_newUserId'] : ""; //三項演算子で出力
                    			?>
                		</span>
                		<span class="userIdError_ajax"></span>
                		<label>
                    			<span class="password">パスワード</span>
                    			<input type="password" name="password" placeholder="8文字以上半角英数(大文字含)"><br>
               			</label>
                		<span class="checkInputEmpty_password">
                    			<?php 
                        			echo isset($validationErrors['checkInputEmpty_password'])||!empty($validationErrors['checkInputEmpty_password'])?
                        			$validationErrors['checkInputEmpty_password'] : ""; 
                    			?>
                		</span>
                		<span class="passwordFormat">
                    			<?php 
						echo isset($validationErrors['passwordFormat'])||!empty($validationErrors['passwordFormat'])?
                        			$validationErrors['passwordFormat'] : "";
                    			?>
                		</span>
                		<label>
                    			<span class="passwordConfirm">パスワード再入力</span>
                    			<input type="password" name="passwordConfirm" placeholder="8文字以上半角英数(大文字含)"><br>
                		</label>
                		<span class="checkInputEmpty_passwordConfirm">
                    			<?php 
						echo isset($validationErrors['checkInputEmpty_passwordConfirm'])||!empty($validationErrors['checkInputEmpty_passwordConfirm'])?
                        			$validationErrors['checkInputEmpty_passwordConfirm'] : "";
                    			?>
                		</span>
                		<span class="confirmPassword">
                    			<?php 
						echo isset($validationErrors['confirmPassword'])||!empty($validationErrors['confirmPassword'])?
                        			$validationErrors['confirmPassword'] : "";
                    			?>
                		</span>
                		<label class="check">
                    			<input type="checkbox"><span class="check">パスワードを表示する</span>
                		</label>
                		<input type="submit" value="新規登録">
	    		</form>
        	</div>
		<?php 
        		if(isset($_SESSION['validationErrors'])||!empty($_SESSION['validationErrors'])) {
            			$_SESSION['validationErrors'] = array();    
        		}
		?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="../js/layout.js"></script>
    		<script src="../js/signUp.js"></script>
	</body>
</html>
