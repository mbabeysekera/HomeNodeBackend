<?php
trait Database {
	private $db_user = "root";
	private $db_pwd  = "";
	private $db_name = "useraccounts";
	public $db;
	
	public function __construct() {
		$this->db = new PDO("mysql:host=localhost;dbname=" . $this->db_name . ";charset=utf8", $this->db_user, $this->db_pwd );
		$this->db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
}
?>