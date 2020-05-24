<?php 
	require_once("../model/hnModel.php"); 
	require_once("../model/hnAuthModel.php");
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json");
	
	$node = new HomeNodeClass();
	$auth = new UserAuthClass();
?>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$hn_datafileds = array("user_id", "outdoor", "livingroom", "kitchen", 
			"garage", "bedroom", "bathroom", "access_token");
		$node_post = array();
		$node_post["user_data"] = array();
		
		if (check_HnDataAvailable($hn_datafileds, $_POST)) {
			$auth->set_UserId($_POST["user_id"]);
			$auth->set_AccessToken($_POST["access_token"]);
			$auth_result = $auth->validate_Token();
			if (count($auth_result) == 1) {
				$node_post["valid"] = true;
				dataToBeSend();
				$is_nodeUpdated = $node->set_NodeData();
				
				if ($is_nodeUpdated) {
					http_response_code(201);
					$user_data["update"] = "successful";
				} else {
					http_response_code(400);
					$user_data["update"] = "error";
				}
				$node_post["user_data"] = $user_data;
			} else {
				http_response_code(401);
				$node_post["user_data"] = "Unauthorized";
				$node_post["valid"] = false;
				$node_post["error"] = "Access Denied";
			}
			
		}else {
			http_response_code(404);
			$node_post["rejected"] = "Wrong Request!";
		}
		echo json_encode($node_post);
	} 
	
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$node_get = array();
		$node_get["user_data"] = array();
		
		if (check_HnDataAvailable(array("user_id", "access_token"), $_GET)){
			$auth->set_UserId($_GET["user_id"]);
			$auth->set_AccessToken($_GET["access_token"]);
			$auth_result = $auth->validate_Token();
			
			if (count($auth_result) == 1) {
				if (!isset($_GET["ses"])) {
					$node_get["valid"] = true;
					$node->set_UserId($_GET["user_id"]);
					$node_result = $node->get_NodeData();
					if (count($node_result) == 1) {
						extract($node_result[0]);
						$user_data = array(
							"outdoor"		=> $outdoor,
							"livingroom" 	=> $livingroom,
							"kitchen"		=> $kitchen,
							"garage" 		=> $garage,
							"bedroom"		=> $bedroom,
							"bathroom" 		=> $bathroom,
							"temperature" 	=> $temperature
						);
						$node_get["user_data"] = $user_data;
					} else {
						http_response_code(404);
						$node_get["user_data"] = array("user" => "Not available");
						$node_get["valid"] = true;
					}
				} else {
					$node_get["user_data"] = array("user_id" => $auth_result[0]["user_id"]);
					$node_get["valid"] = true;
				}
			} else {
				http_response_code(401);
				$node_get["user_data"] = array("request" => "Unauthorized");
				$node_get["valid"] = false;
				$node_get["error"] = "Access Denied";
			}
		} else {
			http_response_code(404);
			$node_get["user_data"] = array("request" => "rejected");
			$node_get["valid"] = false;
			$node_get["error"] = "Access Denied";
		}
		echo json_encode($node_get);
	}
	
	function check_HnDataAvailable($data, $request) {
		foreach($data as $val) {
			if (!isset($request[$val])) {
				return false;
			}
		}
		return true;
	}
	
	function dataToBeSend() {
		global $node;
		$node->set_UserId($_POST["user_id"]);
		$node->set_Outdoor($_POST["outdoor"]);
		$node->set_LivingRoom($_POST["livingroom"]);
		$node->set_Kitchen($_POST["kitchen"]);
		$node->set_Garage($_POST["garage"]);
		$node->set_Bedroom($_POST["bedroom"]);
		$node->set_Bathroom($_POST["bathroom"]);
		$node->set_LastModified(time());
	}
?>