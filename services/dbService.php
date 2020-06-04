<?php
	require_once("../db/config.php");
?>
<?php
	class DbServiceClass {

		use Database;
		
		public function db_ServiceCreate($query) {
			try {
				$stmt = $this->db->prepare($query);
				return $stmt->execute();
			} catch(Exception $e) {
				return false;
			}
		}
		
		public function db_ServiceUpdate($query, $data) {
			try {
				$stmt = $this->db->prepare($query);
				return $stmt->execute($data);
			} catch(Exception $e) {
				return false;
			}
		}
	
		public function db_ServiceSelect($query) {
			try {
				$stmt = $this->db->prepare($query);
				$is_fetched = $stmt->execute();
				if ($is_fetched == true) {
					return $stmt->fetchAll();
				}
			} catch (Exception $e) {
				return array("error" => "db_err");
			}
		}
	
		public function db_ServiceSelectParam($query, $data) {
			try {
				$stmt = $this->db->prepare($query);
				$is_fetched = $stmt->execute($data);
				if ($is_fetched == true) {
					return $stmt->fetchAll();
				}
			} catch(Exception $e) {
				return array("error" => "db_err");
			}
		}
		
		public function db_ServiceInsert($query, $data) {
			try {
				$stmt = $this->db->prepare($query);
				return $stmt->execute($data);
			} catch(Exception $e) {
				return false;
			}
		}
		
		public function db_ServiceDelete($query, $data) {
			try {
				$stmt = $this->db->prepare($query);
				return $stmt->execute($data);
			} catch(Exception $e) {
				return false;
			}
		}
	}
?>