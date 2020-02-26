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
	function __construct($user, $dbpassword, $dbname, $host) {
		$this->user = $user;
		$this->dbpassword = $dbpassword;
		$this->dbname = $dbname;
		$this->host = $host;
		$this->dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
	} 
	
	//メソッド
	//PDOインスタンス作成および初期設定
	public function createPdo() {
		$this->pdo = new PDO($this->getDsn(), $this->getUser(), $this->getDbpassword());
		$this->getPdo()->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        	$this->getPdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
	}
	//SELECT文で取得したデータを返却
	//第一引数にSQL文、第二引数にbindvalu値
	public function select($sql) {
		return $sql;
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
