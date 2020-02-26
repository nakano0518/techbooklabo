<?php


class DbConnect{
	private $user;
	private $dbpassword;
	private $dbname;
	private $host;
	private $dsn;	

	function __construct($user, $dbpassword, $dbname, $host) {
		$this->user = $user;
		$this->dbpassword = $dbpassword;
		$this->dbname = $dbname;
		$this->host = $host;
		$this->dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
	} 
	
	public function getUser() {
		return $this->user;
	}
	
	public function getDsn() {
		return $this->dsn;
	}
}
