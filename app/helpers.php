<?php

/*
	HELPER FUNCTIONS - Add as you need
*/

function _get_db() {
		
	$dsn = 'mysql:host=localhost;port=3306;dbname=inventory';
		
	$options = array(
	    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
	); 

	$username = 'root'; 
	$password = 'root'; 

	$db = new PDO($dsn, $username, $password, $options);

	return $db; 
}

function _escape_mysql($string) {

	$string = str_replace("'", "''", $string);
	$string = str_replace("\\", "\\\\", $string);

	return $string; 
}

function _isset($value) {
	return isset($value) && $value != ""; 
}

function _format_item_description($item) {
	$description = $item["carats"] . " carat " . $item["color"] . " " . $item["clarity"] . " " . $item["shape"];

	if ($item["stone_count"] == 2) {
		$description = "Hand matched pair of " . $description . "s";
	}

	$parts = []; 

	if ($item["cut"] != "n/a") {
		array_push($parts, $item["cut"] . " cut");
	}

	if ($item["polish"] != "n/a") {
		array_push($parts, $item["polish"] . " polish");
	}

	if ($item["symmetry"] != "n/a") {
		array_push($parts, $item["symmetry"] . " symmetry");
	}

	if ($item["fluorescence"] != "n/a") {
		array_push($parts, $item["fluorescence"] . " fluorescence");
	}

	if (!empty($parts)) {
		$description .= " featuring ";
		
		$last = array_pop($parts);

		$details = "";

		if (sizeof($parts) > 1) {
			$details = strtolower(implode(", ", $parts) . " and " . $last . ".");
		} else {
			$details = strtolower($last . ".");
		}

		$first_letter = substr($details, 0,1);
		$vowels = ["a", "e", "i", "o", "u"];

		$description .= in_array($first_letter, $vowels) ? "an " : "a ";
		$description .= $details; 
	} else {
		$description .= ".";
	}



	if ($item["certificate"] != "n/a") {
		$description .= " Certified by " . $item["certificate"] . " (Report No. " . $item["report_no"] . ").";
	}

	return $description; 
}

function _format_param($item) {

	$formatter = []; 
		$formatter["shape"] = [
			"AS" => "Asscher",
			"BAG" => "Baguette",
			"BR" => "Round",
			"CUSH" => "Cushion",
			"EC" => "Emerald",
			"HS" => "Heart",
			"KITE" => "Kite",
			"MQ" => "Marquise",
			"OE" => "Old Euro",
			"OM" => "Old Miner",
			"OV" => "OV",
			"PRIN" => "Princess",
			"PS" => "Pear",
			"RAD" => "Radiant",
			"TRIL" => "Trillion"
		];

		$formatter["certificate"] = [
			"G" => "GIA",
			"E" => "EGL",
			"A" => "AGS",
		];

		$formatter["clarity"] = [
			"300" => "I1",
			"350" => "SI3",
			"400" => "SI2",
			"500" => "SI1",
			"600" => "VS2",
			"650" => "VS1",
			"725" => "VVS2",
			"775" => "VVS1",
			"800" => "IF",
		];

		$formatter["color"] = [
			"700" => "M",
			"710" => "L",
			"720" => "K",
			"730" => "J",
			"740" => "I",
			"750" => "H",
			"760" => "G",
			"770" => "F",
			"780" => "E",
			"790" => "D",
		];

		$formatter["polish"] = [
			"VG" => "Very Good",
			"GD" => "Good",
			"G" => "Good",
			"EX" => "Excellent",
			"ID" => "Ideal",
			"FR" => "Fair",
			"F" => "Fair"
		];

		$formatter["symmetry"] = [
			"VG" => "Very Good",
			"GD" => "Good",
			"G" => "Good",
			"EX" => "Excellent",
			"ID" => "Ideal",
			"FR" => "Fair",
			"F" => "Fair",
			"FG" => "Good",
			"VS" => "n/a",
			"MD" => "n/a",
			"SM" => "n/a",
			"NO" => "n/a"
		];

		$formatter["cut"] = [
			"VG" => "Very Good",
			"GD" => "Good",
			"G" => "Good",
			"EX" => "Excellent",
			"ID" => "Ideal",
			"FR" => "Fair",
			"F" => "Fair",
			"FG" => "Good",
			"VS" => "n/a",
			"MD" => "n/a",
			"SM" => "n/a",
			"NO" => "n/a"
		];

		$formatter["fluorescence"] = [
			"MB" => "Medium",
			"NO" => "No",
			"FT" => "Faint",
			"NON" => "No",
			"Slight B" => "Slight",
			"SB" => "Slight",
			"Moderate B" => "Moderate",
			"ModerateB" => "Moderate",
			"Faint B" => "Faint",
			"FNT" => "Faint",
			"STG" => "Strong",
			"FAINT B" => "Faint",
			"B" => "n/a",
			"SLIGHT B" => "Slight",
			"FY" => "Faint Yellow",
			"FB" => "Faint",
			"Distinct B" => "Distinct",
			"Distint B" => "Distinct",
			"Slight B" => "Slight",
			"Sligh B" => "Slight",
			"SL" => "Slight",
			"VF" => "n/a",
			"MDF" => "n/a",
			"MDP" => "n/a",
			"VST Blue" => "Very Strong",
			"VerySlight" => "Slight",
			"Sighlt B" => "Slight"
		];

		$formatter["certificate_link"] = [
			"G" => "http://www.gia.edu/cs/Satellite?pagename=GST%2FDispatcher&childpagename=GIA%2FPage%2FSearchResults&c=Page&cid=1355954564260&q=",
			"E" => "http://www.eglusa.com/verify-a-report-results/?st_num=",
			"A" => "http://www.google.com"
		];

		$formatter["culet"] = [
			"SL" => "Slight",
			"NO" => "None",
			"VS" => "Very Small",
			"SM" => "Small",
			"VG" => "Very Large",
			"MD" => "Medium",
			"ME" => "Medium",
			"N0" => "None",
			"LG" => "Large",
			"VL" => "Very Large",
			"NI" => "None"
		];

	foreach ($item as $key => $value) {
		if (!empty($formatter[$key])) {
			if (!empty($formatter[$key][$value])) {
				$item[$key] = $formatter[$key][$value];
			}
		}
	}

	if (!empty($item["certificate"]) && !empty($item["report_no"])) {
		if ($item["report_no"] != "n/a" && $item["certificate"] != "n/a") {
			$item["certificate_link"] .= $item["report_no"];
		}
	}

	if ($item["measurements"] != "n/a") {
		$measurements = explode("x", strtolower($item["measurements"]));

		if (sizeof($measurements) == 3) {
			$item["length"] = !empty(floatval($measurements[0])) ? floatval($measurements[0]) : "n/a";
			$item["width"] = !empty(floatval($measurements[1])) ? floatval($measurements[1]) : "n/a";
			$item["depth"] = !empty(floatval($measurements[2])) ? floatval($measurements[2]) : "n/a";
			$item["measurements"] = implode(" x ", $measurements) . " mm";

			$min = min($item["length"], $item["width"]);
			$max = max($item["length"], $item["width"]);

			$item["lw_ratio"] = ($min < $max && $min > 0 && $max > 0) ? round($max/$min,2) : "n/a"; 

		} else {
			$item["length"] = "n/a";
			$item["width"] = "n/a";
			$item["depth"] = "n/a";
			$item["lw_ratio"] = "n/a";
		}
	}

	if (is_numeric($item["color"])) {
		$item["color"] = $item["color_grade"];
	}

	unset($item["color_grade"]);

	return $item; 
}

function _sql_col($col) {
	$defs = [];
		$defs["shape"] = "Shape";
		$defs["price"] = "Total Parcel";
		$defs["carats"] = "actual cts";
		$defs["cut"] = "cut grade";
		$defs["color"] = "color code";
		$defs["color_grade"] = "color grade";
		$defs["clarity"] = "grade non cert clarity";
		$defs["depth_p"] = "depth";
		$defs["table_p"] = "table";
		$defs["polish"] = "polish";
		$defs["symmetry"] = "symmetry";
		$defs["fluorescence"] = "fluorescence";
		$defs["certificate"] = "cert from";
		$defs["report_no"] = "cert #";
		$defs["girdle"] = "girdle";
		$defs["culet"] = "culet";
		$defs["measurements"] = "measurements";
		$defs["stone_count"] = "actual stones";
		$defs["id"] = "lot number";

	return $defs[$col]; 
}

function _format_item($result) {
	$item = []; 

	// basic info

	$item["id"] = !empty($result[_sql_col("id")]) ? $result[_sql_col("id")] : "n/a";
	$item["shape"] = !empty($result[_sql_col("shape")]) ? $result[_sql_col("shape")] : "n/a";
	$item["price"] = !empty($result[_sql_col("price")]) ? floatval($result[_sql_col("price")]) : "n/a";
	$item["stone_count"] = !empty($result[_sql_col("stone_count")]) ? floatval($result[_sql_col("stone_count")]) : "n/a";

	// four cs

	$item["carats"] = !empty($result[_sql_col("carats")]) ? floatval($result[_sql_col("carats")]) : "n/a";
	$item["cut"] = !empty($result[_sql_col("cut")]) ? $result[_sql_col("cut")] : "n/a";
	$item["color"] = !empty($result[_sql_col("color")]) ? $result[_sql_col("color")] : "n/a";
	$item["color_grade"] = !empty($result[_sql_col("color_grade")]) ? $result[_sql_col("color_grade")] : "n/a";
	$item["clarity"] = !empty($result[_sql_col("clarity")]) ? $result[_sql_col("clarity")] : "n/a";
	
	// detailed info

	$item["depth_p"] = !empty($result[_sql_col("depth_p")]) ? floatval($result[_sql_col("depth_p")]) : "n/a";
	$item["table_p"] = !empty($result[_sql_col("table_p")]) ? floatval($result[_sql_col("table_p")]) : "n/a";
	$item["polish"] = !empty($result[_sql_col("polish")]) ? $result[_sql_col("polish")] : "n/a";
	$item["symmetry"] = !empty($result[_sql_col("symmetry")]) ? $result[_sql_col("symmetry")] : "n/a";
	$item["fluorescence"] = !empty($result[_sql_col("fluorescence")]) ? $result[_sql_col("fluorescence")] : "n/a";
	
	$item["girdle"] = !empty($result[_sql_col("girdle")]) ? $result[_sql_col("girdle")] : "n/a";
	$item["culet"] = !empty($result[_sql_col("culet")]) ? $result[_sql_col("culet")] : "n/a";
	$item["measurements"] = !empty($result[_sql_col("measurements")]) ? $result[_sql_col("measurements")] : "n/a";

	// certificate

	$item["certificate"] = !empty($result[_sql_col("certificate")]) ? $result[_sql_col("certificate")] : "n/a";
	$item["certificate_link"] = !empty($result[_sql_col("certificate")]) ? $result[_sql_col("certificate")] : "n/a";
	$item["report_no"] = !empty($result[_sql_col("report_no")]) ? $result[_sql_col("report_no")] : "n/a";

	$item["carats"] = ($item["stone_count"] == 2) ? round($item["carats"]/2,2) : $item["carats"]; 

	$item["description"] = _format_item_description(_format_param($item));

	unset($item["stone_count"]);

	return _format_param($item); 
}

function _format_results($old_results) {

	$prep = []; 

	foreach ($old_results as $result) {

		$item = _format_item($result);

		array_push($prep, $item); 
	}

	return $prep; 
}

function _sort_results($old_results) {
	
	foreach ($old_results as $key => $value) {
		if ($value[$_GET["sort"]] == "n/a") {
			unset($old_results[$key]);
		}
	}

	if ($_GET["sort"] == "clarity") {
		$all = ["FL", "IF", "VVS1", "VVS2", "VS1", "VS2", "SI1", "SI2", "SI3", "I1", "I2", "I3"];
		foreach ($old_results as $key => $value) {
			if (array_search($value["clarity"], $all) === False) {
				unset($old_results[$key]);
			}
		}
	}

	function _cmp($a, $b) {
	    
	    $col = $_GET["sort"];
	    $order = isset($_GET["order"]) ? $_GET["order"] : -1;
	    $order *= ($col == "color") ? -1 : 1;

	    $a = $a[$col];
	    $b = $b[$col];

	    if ($col == "cut" || $col == "polish" || $col == "symmetry") {
	    	$val = ["e" => 6, "i" => 5, "v" => 4, "g" => 3, "f" => 2];
	    	$a = $val[strtolower(substr($a, 0,1))];
	    	$b = $val[strtolower(substr($b, 0,1))];
	    }

	    if ($col == "clarity") {
	    	$val = ["FL" => 10000, "IF" => 9000, "VVS1" => 8000, "VVS2" => 7000, "VS1" => 1600, "VS2" => 1500, "SI1" => 1400, "SI2" => 1300, "SI3" => 1200, "I1" => 1100, "I2" => 1000, "I3" => 900];
	    	$a = $val[strtoupper($a)];
	    	$b = $val[strtoupper($b)];
	    }

	    if ($a == $b) {
	        return 0;
	    }
	    
	    if ($order == 1) {
	    	return ($a < $b) ? -1 : 1;
	    }

	    return ($a > $b) ? -1 : 1;
	    	
	}

	usort($old_results, "_cmp");

	return array_values($old_results);

}


function _time_ago($etime) {

    if ($etime < 1) {
        return "0s";
    }

    $a = array( 365 * 24 * 60 * 60  =>  'yr',
                 30 * 24 * 60 * 60  =>  'mo',
                      24 * 60 * 60  =>  'd',
                           60 * 60  =>  'h',
                                60  =>  'm',
                                 1  =>  's'
                );

    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    }
}


function _validate($endpoints) {
	for ($i=0; $i < sizeof($endpoints); $i++) { 

		$attribute = $endpoints[$i]; 

		if (isset($_GET[$attribute]) && trim($_GET[$attribute]) != "") {
			$_GET[$attribute] = trim($_GET[$attribute]); 

			// if ($attribute == "email" or $attribute == "first_name" or $attribute == "last_name") {
			// 	$_GET[$attribute] = strtolower($_GET[$attribute]);
			// }

		} else {
			_respondWithError("missing parameters","Missing " . $endpoints[$i]);
			return False; 
		}
	}

	return True; 
}

function _log($endpoint, $response) {
	$log = new Log();
	$log->endpoint = $endpoint; 
	$log->response = $response["error_description"]; 
	$log->get_params = json_encode($_GET); 
	$log->post_params = json_encode($_POST); 
	$log->save(); 
}

function _validateWithoutError($endpoints) {
	for ($i=0; $i < sizeof($endpoints); $i++) { 

		$attribute = $endpoints[$i]; 

		if (isset($_GET[$attribute]) && trim($_GET[$attribute]) != "") {
			$_GET[$attribute] = trim($_GET[$attribute]); 
			// if ($attribute == "email" or $attribute == "first_name" or $attribute == "last_name") {
			// 	$_GET[$attribute] = strtolower($_GET[$attribute]);
			// }
		} else {
			return False; 
		}
	}

	return True; 
}

function _respond($input) {
	$array = [];
	$array["count"] = sizeof($input);
	$array["data"] = $input; 
	echo json_encode($array);
	//_log($endpoint, "success"); 
}

function _respondWithMessage($endpoint, $input, $message) {
	$array = [];
	$array["error"] = False;
	$array["error_description"] = ""; 
	$array["message"] = True;
	$array["message_description"] = $message; 
	$array["data"] = $input; 
	echo json_encode($array); 
	//_log($endpoint, "success from _respondWithMessage"); 
}

function _respondWithError($endpoint, $message) {
	$array = [];
	$array["error"] = True; 
	$array["error_description"] = $message;
	$array["message"] = False; 
	$array["message_description"] = ""; 
	$array["data"] = []; 
	echo json_encode($array);
	//_log($endpoint, $array);  
}

function _hash($string) {
	//
	return hash('sha256', $string);
}


?>