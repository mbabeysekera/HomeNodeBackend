<?php
	require_once("dbService.php");
	$init_db = new DbServiceClass();
?>
<?php
echo "started";
	$hn_users = "CREATE TABLE IF NOT EXISTS hn_users(
		user_id VARCHAR(10) NOT NULL PRIMARY KEY,
		username VARCHAR(50) NOT NULL,
		password VARCHAR(40) NOT NULL,
		last_login VARCHAR(32) NOT NULL DEFAULT '0'
	)";
	
	$hn_user_auth = "CREATE TABLE IF NOT EXISTS hn_user_auth (
		id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		user_id VARCHAR(10) NOT NULL,
		access_token VARCHAR(64) NOT NULL,
		time_created VARCHAR(32) NOT NULL DEFAULT '0')
	";
	
	$hn_device_data = "
		CREATE TABLE IF NOT EXISTS hn_device_data (
		id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		user_id VARCHAR(10) NOT NULL,
		outdoor TINYINT(1) NOT NULL DEFAULT 0,
		livingroom TINYINT(1) NOT NULL DEFAULT 0,
		kitchen TINYINT(1) NOT NULL DEFAULT 0,
		garage TINYINT(1) NOT NULL DEFAULT 0,
		bedroom TINYINT(1) NOT NULL DEFAULT 0,
		bathroom TINYINT(1) NOT NULL DEFAULT 0,
		temperature VARCHAR(3) NOT NULL DEFAULT 0,
		last_modified VARCHAR(32) NOT NULL DEFAULT '0')
	";
	
	$is_user_created = $init_db->db_ServiceCreate($hn_users);
	if ($is_user_created) {
		$is_user_auth_created = $init_db->db_ServiceCreate($hn_user_auth);
		if ($is_user_auth_created) {
			$is_device_data_created = $init_db->db_ServiceCreate($hn_device_data);
			echo "DB Created!";
		}
	}
?>