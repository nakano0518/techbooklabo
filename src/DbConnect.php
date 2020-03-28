<?php

class DbConnect{
	//プロパティ
	private $user;
	private $dbpassword;
	private $dbname;
	private $host;
	private $dsn;	
	private $pdo;

	//コンストラクタ
	public function __construct($user, $dbpassword, $dbname, $host) {
		$this->user = $user;
		$this->dbpassword = $dbpassword;
		$this->dbname = $dbname;
		$this->host = $host;
		$this->dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
	} 
	
	//メソッド
	//PDOインスタンス作成および初期設定
	public function createPdo() {
		$this->pdo = new PDO($this->dsn, $this->user, $this->dbpassword);
		$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        	$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
	}
	//SELECT文でデータをfetch取得
	//第一引数にSQL文、
	//第二引数にbindvalu値は、[[],[],[],...]の形の2次元配列、その中の1次元配列の中にbindValue時の引数3つを入れて渡す
	public function selectfetch($sql, $bindValues=false) {
		$stm = $this->pdo->prepare($sql);
		if($bindValues){
			for($i = 0; $i < count($bindValues); $i++) {
				$stm->bindValue($bindValues[$i][0], $bindValues[$i][1], $bindValues[$i][2]);
			}
		}
		$stm->execute();
		$data = $stm->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	//SELECT文でデータをfetchAll取得
	//第一引数にSQL文、
	//第二引数にbindvalu値は、[[],[],[],...]の形の2次元配列、その中の1次元配列の中にbindValue時の引数3つを入れて渡す
	public function selectfetchAll($sql, $bindValues=false) {
		$stm = $this->pdo->prepare($sql);
		if($bindValues){
			for($i = 0; $i < count($bindValues); $i++) {
				$stm->bindValue($bindValues[$i][0], $bindValues[$i][1], $bindValues[$i][2]);
			}
		}
		$stm->execute();
		$data = $stm->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}
	//DML文(Data Manipulation Language : INSERT, DELETE, UPDATE)の実行
	public function dml($sql, $bindValues=false) {
		$dml = $this->pdo->prepare($sql);
		if($bindValues) {	
			for($i = 0; $i < count($bindValues); $i++) {
				$dml->bindValue($bindValues[$i][0], $bindValues[$i][1], $bindValues[$i][2]);
			}
		}
		$dml->execute();
	}

	
	
	//ゲッター
	public function getUser() {
		return $this->user;
	}
	public function getDsn() {
		return $this->dsn;
	}
	public function getDbpassword() {
		return $this->dbpassword;
	}
	public function getPdo() {
		return $this->pdo;
	}

}
