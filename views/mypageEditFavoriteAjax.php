<?php
		session_start();
		
		require_once '../config/envLoad.php';
    	require_once "../lib/util.php";

	

    	if(isset($_POST) && !empty($_POST)){
        	$bookNo = es($_POST['bookNo']);
        	$userId = es($_POST['userId']);
    	}
?>
<?php
		try{
			$db = new DbConnect(getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_HOST"));
			$db->createPdo();
	    		$sql = "DELETE FROM t_good WHERE userId = :userId AND no = :no";
			$delete = $db->dml($sql,[
	    			[':userId', $userId, PDO::PARAM_STR],
	    			[':no', $bookNo, PDO::PARAM_STR]
			]);
		}catch(Exception $e){
	    		echo $e->getMessage();
		}
?>
