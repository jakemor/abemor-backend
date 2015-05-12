<?php
include "./settings.php";

// optionally include some usefull functions
include "helpers.php";

// API Endpoints


function describe() {
	if (_validate(["col"])) {

		$db = _get_db();
		$sql = "SELECT * FROM `item` WHERE `actual stones` = 1 AND CHAR_LENGTH(`color grade`) = 1";
		$query = $db->query($sql);
		$results = $query->fetchAll(PDO::FETCH_ASSOC);

		$col = $_GET["col"];
		$hash = []; 

		foreach ($results as $result) {
			if (!empty($result[$col])) {
				if (!empty($hash[$result[$col]])) {
					$hash[$result[$col]]++;
				} else {
					$hash[$result[$col]] = 1; 
				} 
			}
		}

		$results = []; 
		$results["count"] = count($hash);
		$results["data"] = $hash;
		print_r(json_encode($results)); 
	}
}      

function diamonds() {
	$db = _get_db(); 
	$sql = "SELECT * FROM `item` WHERE `actual stones` = 1 AND CHAR_LENGTH(`color grade`) = 1";

 
	if (isset($_GET["shape"])) {
		$array = explode(",", $_GET["shape"]);

		$sql .= " AND (";

		$parts = []; 

		foreach ($array as $value) {
			array_push($parts, "`Shape` = '{$value}'"); 
		}

		$sql .= implode(" OR ", $parts); 

		$sql .= ")";
	}

	if (isset($_GET["clarity"])) {
		$array = explode(",", $_GET["clarity"]);
		$sql .= " AND `clarity code` BETWEEN {$array[0]} AND {$array[1]}"; 
	}

	if (isset($_GET["cert"])) {
		$cert = $_GET["cert"];
		$sql .= " AND `cert #` = '{$cert}'"; 
	}

	$query = $db->query($sql);
	$result = $query->fetchAll(PDO::FETCH_ASSOC);


	if (isset($_GET["all"])) {
		print_r(json_encode($result));
	} else {
		print_r(json_encode(_format_results($result))); 
	}
	
}

function example() {
	$endpoint = "createUser"; 
	if (_validate(["facebook_id", "first_name", "last_name", "gender", "email"])) {
		$user = new User(); 
		$found = $user->get("facebook_id", $_GET["facebook_id"]); 
		if ($found === False) {
			$user->facebook_id = $_GET["facebook_id"];
			$user->first_name = ucfirst(strtolower($_GET["first_name"]));
			$user->last_name = ucfirst(strtolower($_GET["last_name"]));
			$user->gender = strtolower($_GET["gender"]);
			$user->email = strtolower($_GET["email"]);
			$user->save();
			_respond($endpoint, $user); 
		} else {
			_respondWithError($endpoint, "That account has already been created.");
		}
		
	}
}

// Must include this function. You can change its name in settings.php
function home() {
	// CODE HERE
	include("views/home.php"); 
}

// Must include this function. You can change its name in settings.php
function notfound() {
	// CODE HERE

	include("views/notfound.php"); 
}


// Useful for system wide announcments / debugging
function _everypage() {

}

?>
