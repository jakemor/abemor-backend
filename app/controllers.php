<?php
include "./settings.php";

// optionally include some usefull functions
include "helpers.php";

// API Endpoints


function describe() {
	if (_validate(["col"])) {

		$db = _get_db();
		$sql = "SELECT * FROM `item` WHERE `actual stones` = 1";
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

function search() {
	$db = _get_db();

	$sql = "";
	$type = isset($_GET["t"]) ? $_GET["t"] : "";
	
	$stone_count = _sql_col("stone_count");
	$color = _sql_col("color");

	switch ($type) {

		case 'white':
			$sql = "SELECT * FROM `item` WHERE `actual stones` = 1 AND CHAR_LENGTH(`color grade`) = 1";
			break;

		case 'fancy':
			$sql = "SELECT * FROM `item` WHERE `actual stones` = 1 AND `color code` < 700 AND `color code` > 1";
			break;

		case 'pair':
			$sql = "SELECT * FROM `item` WHERE `actual stones` = 2 AND CHAR_LENGTH(`color grade`) = 1";
			break;
		
		default:
			echo "Missing type.";
			return;  
			break;
	}

		

	// search by unique id numbers
	
	if (isset($_GET["cert"])) {
		$cert = $_GET["cert"];
		$col = _sql_col("report_no");
		$sql .= " AND `{$col}` = '{$cert}'"; 
	}

	if (isset($_GET["id"])) {
		$id = $_GET["id"];
		$col = _sql_col("id");
		$sql .= " AND `{$col}` = '{$id}'"; 
	}

 	// search by shape
	
	if (isset($_GET["shape"])) {
		$array = explode(",", $_GET["shape"]);

		$sql .= " AND (";

		$parts = []; 

		$col = _sql_col("shape");

		foreach ($array as $value) {
			array_push($parts, "`{$col}` = '{$value}'"); 
		}

		$sql .= implode(" OR ", $parts); 

		$sql .= ")";
	}

	// search by 4cs, polish, symmetry

	$same = ["cut", "polish", "symmetry"];

	foreach ($same as $param) {
		if (isset($_GET[$param])) {

			$params = explode(",", $_GET[$param]);

			for ($i=0; $i < sizeof($params); $i++) { 
				$params[$i] = strtoupper(substr($params[$i], 0,1));
			}

			$all = ["F", "G", "V", "E", "I"];

			$min_key = array_search($params[0], $all); 
			$max_key = array_search($params[1], $all);

			$search_params = []; 

			for ($i=$min_key; $i <= $max_key; $i++) { 

				if ($all[$i] == "F") {
					array_push($search_params, "FR");
					array_push($search_params, "F");
				}

				if ($all[$i] == "G") {
					array_push($search_params, "G");
					array_push($search_params, "GD");
				}

				if ($all[$i] == "V") {
					array_push($search_params, "VG");
				}

				if ($all[$i] == "E") {
					array_push($search_params, "EX");
				}

				if ($all[$i] == "I") {
					array_push($search_params, "ID");
				}

			}

			$sql .= " AND (";

			$parts = []; 

			$col = _sql_col($param);

			foreach ($search_params as $value) {
				array_push($parts, "`{$col}` = '{$value}'"); 
			}

			$sql .= implode(" OR ", $parts); 

			$sql .= ")";
		}
	}

	if (isset($_GET["color"])) {

		$params = explode(",", $_GET["color"]);

		for ($i=0; $i < sizeof($params); $i++) { 
			$params[$i] = strtoupper(substr($params[$i], 0,1));
		}

		$all = [
			"D" => 790, 
			"E" => 780, 
			"F" => 770, 
			"G" => 760, 
			"H" => 750, 
			"I" => 740, 
			"J" => 730, 
			"K" => 720, 
			"L" => 710, 
			"M" => 700
		];

		$lower = $all[$params[1]];
		$upper = $all[$params[0]];

		$col = _sql_col("color");

		$sql .= " AND (`{$col}` BETWEEN '{$lower}' AND '{$upper}')";
	}

	if (isset($_GET["clarity"])) {

		$params = explode(",", $_GET["clarity"]);

		for ($i=0; $i < sizeof($params); $i++) { 
			$params[$i] = strtoupper($params[$i]);
		}


		$all = ["FL", "IF", "VVS1", "VVS2", "VS1", "VS2", "SI1", "SI2", "SI3", "I1", "I2", "I3"];

		$min_key = array_search($params[0], $all); 
		$max_key = array_search($params[1], $all);

		$search_params = []; 

		for ($i=$min_key; $i <= $max_key; $i++) { 
			array_push($search_params, $all[$i]);
		}

		$sql .= " AND (";

		$parts = []; 

		$col = _sql_col("clarity");

		foreach ($search_params as $value) {
			array_push($parts, "`{$col}` = '{$value}'"); 
		}

		$sql .= implode(" OR ", $parts); 

		$sql .= ")";
	}

	// search by price and carat

	$same = ["price", "carats", "depth", "length", "width"];

	foreach ($same as $value) {
		if (isset($_GET[$value])) {

			$params = explode(",", $_GET[$value]);

			for ($i=0; $i < sizeof($params); $i++) { 
				$params[$i] = floatval($params[$i]);
			}

			$lower = ($type == "pair" && $value == "carats") ? $params[0]*2 : $params[0];
			$upper = ($type == "pair" && $value == "carats") ? $params[1]*2 : $params[1];

			$col = _sql_col($value);

			$sql .= " AND (`{$col}` BETWEEN {$lower} AND {$upper})";
		}
	}



	$query = $db->query($sql);
	$result = $query->fetchAll(PDO::FETCH_ASSOC);

	if (isset($_GET["all"])) {
		print_r(json_encode($result));
		return;
	}
	
	$result = _format_results($result);
	
	if (isset($_GET["sort"])) {
		$result = _sort_results($result, $_GET["sort"]);
	}

	//_respond($result);
	echo "done";
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
