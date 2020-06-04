<?php
	require_once("../model/usersModel.php");
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json");
?>
<?php
	$request = $_SERVER["REQUEST_METHOD"];
	$regDetails = array();
	$regDetails["reg_data"] = array();
	switch($request) {
		case "GET": check_UserNameAvailability(); break;
		case "POST": new_User_Register(); break;
	}
	
	function check_UserNameAvailability() {
		if (check_RegFieldsAvailble($regFields = array("username"))) {
			$reg = new LoginClass();
			$reg->set_Username($_GET["username"]);
			$user = $reg->check_ForUserNames();
			if (count($user) == 1) {
				$regDetails["reg_data"] = array(
					"message" 	=> "User already exist",
					"available"	=> false 
					);
				$regDetails["valid"] = true;
			} else {
				$regDetails["reg_data"] = array(
					"message" 	=> "No User for this name",
					"available"	=> true 
					);
				$regDetails["valid"] = true;
			}
		} else {
			http_response_code(404);
			$regDetails["reg_data"] = "Invalid Request";
			$regDetails["valid"] = false;
			$regDetails["error"] = "Empty parameters!";
		}
		echo json_encode($regDetails);
	}
	
	function new_User_Register() {
		if (check_RegFieldsAvailble(array("username", "password"))) {
			$reg = new LoginClass();
			$reg->set_Username($_POST["username"]);
			$reg->set_Password($_POST["password"]);
			$reply = $reg->create_User();
			if ($reply["updated"]) {
				$regDetails["reg_data"] = array(
					"message" 		=> "User has been created",
					"successful"	=> true
					);
				$regDetails["valid"] = true;
			} else {
				if ($reply["message"] == "error1") {
					$regDetails["reg_data"] = array(
					"message" 		=> "User cannot be created",
					"available"	=> true, 
					"successful"	=> false
					);
					$regDetails["valid"] = true;
				} else {
					$regDetails["reg_data"] = array(
					"message" 		=> "User already exist",
					"available"	=> false, 
					"successful"	=> false
					);
					$regDetails["valid"] = true;
				}
			}
		} else {
			http_response_code(404);
			$regDetails["reg_data"] = "Invalid Request";
			$regDetails["valid"] = false;
			$regDetails["error"] = "Empty parameters!";
		}
		echo json_encode($regDetails);
	}
	
	function check_RegFieldsAvailble($regFields) {
		
		foreach($regFields as $regData) {
			if (!isset($_REQUEST[$regData])){
				return false;
			}
		}
		return true;
	}
?>