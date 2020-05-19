<?php require_once("../services/dbService.php"); ?>
<?php
	class HomeNodeClass extends DbServiceClass{
		private $user_id;
		private $outdoor;
		private $livingroom;
		private $kitchen;
		private $garage;
		private $bedroom;
		private $bathroom;
		private $temperature;
		private $last_modified;
		
		public function get_UserId() {
			return $this->user_id;
		}
		
		public function set_UserId($user_id) {
			$this->user_id = $user_id;
		}
		
		public function get_Outdoor() {
			return $this->outdoor;
		}
		
		public function set_Outdoor($outdoor) {
			$this->outdoor = $outdoor;
		}
		
		public function get_LivingRoom() {
			return $this->livingroom;
		}
		
		public function set_LivingRoom($livingroom) {
			$this->livingroom = $livingroom;
		}
		
		public function get_Kitchen() {
			return $this->kitchen;
		}
		
		public function set_Kitchen($kitchen) {
			$this->kitchen = $kitchen;
		}
		
		public function get_Garage() {
			return $this->garage;
		}
		
		public function set_Garage($garage) {
			$this->garage = $garage;
		}
		
		public function get_Bedroom() {
			return $this->bedroom;
		}
		
		public function set_Bedroom($bedroom) {
			$this->bedroom = $bedroom;
		}
		
		public function get_Bathroom() {
			return $this->bathroom;
		}
		
		public function set_Bathroom($bathroom) {
			$this->bathroom = $bathroom;
		}
		
		public function get_Temperature() {
			return $this->temperature;
		}
		
		public function set_Temperature($temperature) {
			$this->temperature = $temperature;
		}
		
		public function get_LastModified() {
			return $this->last_modified;
		}
		
		public function set_LastModified($last_modified) {
			$this->last_modified = $last_modified;
		}
		
		public function get_NodeData() {
			$query = "SELECT * FROM hn_device_data WHERE user_id=?";
			return $this->db_ServiceSelect($query, [$this->user_id]);
		}
		
		public function set_NodeData() {
			$query = "UPDATE hn_device_data SET outdoor=?, livingroom=?, kitchen=?, 
					garage=?, bedroom=?, bathroom=?, temperature=?, last_modified=? WHERE user_id=?";
			$data = [$this->outdoor, $this->livingroom, $this->kitchen, $this->garage, $this->bedroom, 
					$this->bathroom, $this->temperature, $this->last_modified, $this->user_id];
			$is_NodeDataUpdated = $this->db_ServiceUpdate($query, $data);
			if (!$is_NodeDataUpdated) {
				return false;
			}
			return true;
		}
	}
?>