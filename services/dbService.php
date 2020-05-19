<?php
	require_once("../db/config.php");
?>
<?php
	class DbServiceClass {

		use Database;
		
		public function db_ServiceUpdate($query, $data) {
			$stmt = $this->db->prepare($query);
			return $stmt->execute($data);
		}
	
		public function db_ServiceSelect($query, $data) {
			$stmt = $this->db->prepare($query);
			$is_fetched = $stmt->execute($data);
			if ($is_fetched == true) {
				return $stmt->fetchAll();
			}
			return array("error" => "db_err");
		}
	}
?>