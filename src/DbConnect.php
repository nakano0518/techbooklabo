<?php
require_once '../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable('../../env/');
$dotenv->load();

class DbConnect {
	//プロパティ
	private $user;
	private $dbpassword;
	private $dbname;
	private $host;
	private $dsn;

	//コンストラクタ
	function __construct() {
		$this->user = getenv('DB_USERNAME');
		$this->dbpassword = getenv('DB_PASSWORD');
		$this->dbName = getenv('DB_DATABASE');
		$this->host = getenv('DB_HOST');
		$this->dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8";
	}
	
	//メソッド
	//PDOインスタンスの生成と初期設定
	public function dbConnect() {
		$pdo = new PDO($this->dsn, $this->user, $this->dbpassword);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);//プリペアドステートメントのエミュレーション無効
        	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//例外がスローされる設定にする
		return $pdo;
	}
	
	//ゲッター
	public function　getUser() {
		return $this->user;
	} 

	
	
}
