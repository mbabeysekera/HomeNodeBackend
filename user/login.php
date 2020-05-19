<?php
	require_once("../model/usersModel.php");
	require_once("../model/hnAuthModel.php");
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json");
	
	$login = new LoginClass();
	$auth = new UserAuthClass();
?>
<?php
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$auth_data = array();
		$auth_data["user_data"] = array();
		
		if (check_AuthFieldsAvailable(array("username", "password"))){
			$login->set_Username($_POST["username"]);
			$login->set_Password($_POST["password"]);
			$result = $login->ckeck_UserCreds();
			if (count($result) == 1) {
				if ($result[0] == "db_err") {
					$auth_data["error"] = "Data cannot be fetched. connection_error!";
				} else {
					$temp_time = time();
					$access_token = hash("sha256", $temp_time);
					
					$login->set_UserId($result[0]["user_id"]);
					$login->set_LastLogin($temp_time);
					$db_loginUpdate_Flag = $login->update_UserLoginTime();
					
					$auth->set_UserId($result[0]["user_id"]);
					$auth->set_AccessToken($access_token);
					$auth->set_TimeCreated($temp_time);
					$db_authUpdate_Flag = $auth->update_Token();
					
					if ($db_loginUpdate_Flag && $db_authUpdate_Flag) {
						$auth_data["valid"]	= true;
						$user_data = array( 		
							"username"		=> $result[0]["username"],
							"user_id"		=> $result[0]["user_id"],
							"time_stamp"	=> $temp_time,
							"access_token"	=> $access_token
						);
						$auth_data["user_data"] = $user_data;
					} else {
						$auth_data["user_data"] = "Unauthorized";
						$auth_data["valid"] = false;
						$auth_data["error"] = "Data not updated properly. Try again!";
					}
				}
			} else {
				$auth_data["user_data"] = "Unauthorized";
				$auth_data["valid"] = false;
				$auth_data["error"] = "Invalid username or password!";
			} 
		} else {
			$auth_data["user_data"] = "Unauthorized";
			$auth_data["valid"] = false;
			$auth_data["error"] = "Insufficient details, missing parameter(s)!";
		}
		echo json_encode($auth_data);
	}
	
	function check_AuthFieldsAvailable($credentials) {
		foreach ($credentials as $cred) {
			if (!isset($_POST[$cred])) {
				return false;
			}
		}
		return true;
	}
?>