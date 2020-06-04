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
	
	public function check_ForUserNames() {
		$query = "SELECT * FROM hn_users WHERE username = ?";
		return $this->db_ServiceSelectParam($query, [$this->username]);
	}
	
	public function ckeck_UserCreds() {
		$query = "SELECT * FROM hn_users WHERE username = ? AND password = ?";
		return $this->db_ServiceSelectParam($query, [$this->username, $this->password]);
	}
	
	public function update_UserLoginTime() {
		$query = "UPDATE hn_users SET last_login=? WHERE user_id=?";
		$is_UserLogin_Updated = $this->db_ServiceUpdate($query, [$this->last_login, $this->user_id]);
		if (!$is_UserLogin_Updated) {
			return false;
		}
		return true;
	}
	
	public function create_User() {
		$is_available = $this->check_ForUserNames();
		if (count($is_available) == 0) {
			$query = "SELECT * FROM hn_users ORDER BY user_id DESC LIMIT 1";
			$last_user = $this->db_ServiceSelect($query);
			if ($last_user[0] != "db_err") {
				$last_user_id = $last_user[0]["user_id"];
				$new_user_id =((int) explode("_", $last_user_id)[1] + 1)."";
				$len = (7 - strlen($new_user_id));
				for ($i = 0; $i < $len; $i++) {
					$new_user_id = "0".$new_user_id;
				}
				$this->user_id = "HN_".$new_user_id;
				$this->password = sha1($this->password);
				$query = "INSERT INTO hn_users (user_id, username, password) VALUES (?, ?, ?)";
				$is_user_insert = $this->db_ServiceInsert($query, [$this->user_id, $this->username, $this->password]);
				if ($is_user_insert) {
					$query = "INSERT INTO hn_user_auth (user_id) VALUES (?)";
					$is_auth_insert = $this->db_ServiceInsert($query, [$this->user_id]);
					if ($is_auth_insert) {
						$query = "INSERT INTO hn_device_data (user_id) VALUES (?)";
						$is_device_insert = $this->db_ServiceInsert($query, [$this->user_id]);
						if ($is_device_insert) {
							return array(
								"message"	=> "created",
								"updated"	=> true
								);
						}
					}
				}
			} else {
				return array(
						"message"	=> "error1",
						"updated"	=> false
					);
			}
		} else {
			return array(
				"message"	=> "error2",
				"updated"	=> false
				);
		}
	}
}
?>