<?php require_once("../services/dbService.php"); ?> 
<?php
class LoginClass extends DbServiceClass{
	private $user_id;
	private $username;
	private $password;
	private $last_login;
/* 	public function __construct() {
		$db_instance = new Database();
		$this->db = $db_instance->get_Database();
	} */
	public function set_UserId($user_id) {
		$this->user_id = $user_id;
	}
	
	public function get_UserId() {
		return $this->user_id;
	}
	
	public function get_Username() {
		$query = "SELECT username FROM hn_users WHERE username=?";
		return $this->db_ServiceSelect($query, [$this->username]);
	}
	
	public function set_Username($username) {
		$this->username = $username;
	}
	
	public function set_Password($password) {
		$this->password = $password;
	}
	
	public function set_LastLogin($last_login) {
		$this->last_login = $last_login;
	}
	
	public function get_LastLogin() {
		return $this->last_login;
	}
	
	public function ckeck_UserCreds() {
		$query = "SELECT * FROM hn_users WHERE username = ? AND password = ?";
		return $this->db_ServiceSelect($query, [$this->username, $this->password]);
	}
	
	public function update_UserLoginTime() {
		$query = "UPDATE hn_users SET last_login=? WHERE user_id=?";
		$is_UserLogin_Updated = $this->db_ServiceUpdate($query, [$this->last_login, $this->user_id]);
		if (!$is_UserLogin_Updated) {
			return false;
		}
		return true;
	}
}
?>