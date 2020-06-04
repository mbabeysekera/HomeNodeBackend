<?php require_once("../services/dbService.php"); ?>
<?php
	class UserAuthClass extends DbServiceClass{
		private $user_id;
		private $access_token;
		private $time_created;
		
		public function get_UserId() {
			return $this->user_id;
		}
		
		public function set_UserId($user_id) {
			$this->user_id = $user_id;
		}
		
		public function get_AccessToken() {
			return $this->access_token;
		}
		
		public function set_AccessToken($access_token) {
			$this->access_token = $access_token;
		}
		
		public function get_TimeCreated() {
			return $this->time_created;
		}
		
		public function set_TimeCreated($time_created) {
			$this->time_created = $time_created;
		}
		
		public function update_Token() {
			$query = "UPDATE hn_user_auth SET access_token=?, time_created=? WHERE user_id=?";
			$is_AuthDataUpdated = $this->db_ServiceUpdate($query, [$this->access_token, $this->time_created, $this->user_id]);
			if (!$is_AuthDataUpdated) {
				return false;
			}
			return true;
		}
		
		public function validate_Token() {
			$query = "SELECT * FROM hn_user_auth WHERE user_id=? AND access_token=?";
			return $this->db_ServiceSelectParam($query, [$this->user_id, $this->access_token]);
		}
	}
?>