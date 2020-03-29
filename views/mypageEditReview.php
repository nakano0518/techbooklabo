<?php 
	session_start();

	require_once "../lib/util.php";

	require_once '../vendor/autoload.php';
	$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
	$dotenv->load();

	if(isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
    		$userId = $_SESSION['userId'];
	}else {
		$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		if($_SERVER['HTTPS'] !== null){
   			$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
		}
		header('Location:'.$goBackURL.'/signIn.php');
		exit;   
	}

	if(isset($_POST) && !empty($_POST)) {
    		$bookNo = es($_POST['bookNo']);
    		$reviewId = es($_POST['reviewId']);
    		$reviewContent = es($_POST['reviewContent']);
	}

	try{
		$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
		$db->createPdo();
    		$sql = "UPDATE t_review SET reviewContent = :reviewContent WHERE userId = :userId AND no = :no AND reviewId = :reviewId";
		$update = $db->dml($sql,[
    			[':userId', $userId, PDO::PARAM_STR],
    			[':no', $bookNo, PDO::PARAM_STR],
    			[':reviewId', $reviewId, PDO::PARAM_INT],
    			[':reviewContent', $reviewContent, PDO::PARAM_STR)]
		]);
    		$goBackURL = 'https://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    		if($_SERVER['HTTPS'] !== null){
        		$goBackURL = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
    		}
    		header('Location:'.$goBackURL.'/mypageEdit.php');
    		exit;
	}catch(Exception $e){
    		echo $e->getMessage();
	}
    
