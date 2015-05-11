<?php
include "./settings.php";

// optionally include some usefull functions
include "helpers.php";

// API Endpoints


function test() {
	$user = new User(); 
		$user->username = "test"; 
		$user->save(); 

		print_r($user); 
}      

function diamonds() {
	$db = _get_db(); 
	$sql = "SELECT * FROM `item` WHERE `actual stones` = 1 AND CHAR_LENGTH(`color grade`) = 1";

	$sql .= isset($_GET["shape"]) ? " AND `Shape` = {$_GET['shape']}" : ""; 

	$query = $db->query($sql);
	$result = $query->fetchAll(PDO::FETCH_ASSOC);

	print_r(count($result)); 
	print_r($result); 
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
