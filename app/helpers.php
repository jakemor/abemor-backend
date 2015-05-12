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
			"FG" => "Fair-Good",
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
			"FG" => "Fair-Good",
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



		$formatter["certificate link"] = [
			"G" => "http://www.gia.edu/cs/Satellite?pagename=GST%2FDispatcher&childpagename=GIA%2FPage%2FSearchResults&c=Page&cid=1355954564260&q=",
			"E" => "http://www.eglusa.com/verify-a-report-results/?st_num=",
			"A" => "http://www.google.com"
		];

	foreach ($item as $key => $value) {
		if (!empty($formatter[$key])) {
			if (!empty($formatter[$key][$value])) {
				$item[$key] = $formatter[$key][$value];
			}
		}
	}

	if (!empty($item["certificate"]) && !empty($item["report_no"])) {
		$item["certificate link"] .= $item["report_no"];
	}

	return $item; 
}

function _sql_col($col) {
	$defs = [];
		$defs["shape"] = "Shape";
		$defs["price"] = "Total Parcel";
		$defs["carats"] = "size for website rounded";
		$defs["cut"] = "cut grade";
		$defs["color"] = "color code";
		$defs["clarity"] = "grade non cert clarity";
		$defs["depth"] = "depth";
		$defs["table"] = "table";
		$defs["polish"] = "polish";
		$defs["symmetry"] = "symmetry";
		$defs["fluorescence"] = "fluorescence";
		$defs["certificate"] = "cert from";
		$defs["report_no"] = "cert #";

	return $defs[$col]; 
}

function _format_item($result) {
	$item = []; 

	// basic info

	$item["shape"] = !empty($result[_sql_col("shape")]) ? $result[_sql_col("shape")] : "n/a";
	$item["price"] = !empty($result[_sql_col("price")]) ? floatval($result[_sql_col("price")]) : "n/a";

	// four cs

	$item["carats"] = !empty($result[_sql_col("carats")]) ? floatval($result[_sql_col("carats")]) : "n/a";
	$item["cut"] = !empty($result[_sql_col("cut")]) ? $result[_sql_col("cut")] : "n/a";
	$item["color"] = !empty($result[_sql_col("color")]) ? $result[_sql_col("color")] : "n/a";
	$item["clarity"] = !empty($result[_sql_col("clarity")]) ? $result[_sql_col("clarity")] : "n/a";
	
	// detailed info

	$item["depth"] = !empty($result[_sql_col("depth")]) ? floatval($result[_sql_col("depth")]) : "n/a";
	$item["table"] = !empty($result[_sql_col("table")]) ? floatval($result[_sql_col("table")]) : "n/a";
	$item["polish"] = !empty($result[_sql_col("polish")]) ? $result[_sql_col("polish")] : "n/a";
	$item["symmetry"] = !empty($result[_sql_col("symmetry")]) ? $result[_sql_col("symmetry")] : "n/a";
	$item["fluorescence"] = !empty($result[_sql_col("fluorescence")]) ? $result[_sql_col("fluorescence")] : "n/a";

	// certificate

	$item["certificate"] = !empty($result[_sql_col("certificate")]) ? $result[_sql_col("certificate")] : "n/a";
	$item["certificate link"] = !empty($result[_sql_col("certificate")]) ? $result[_sql_col("certificate")] : "n/a";
	$item["report_no"] = !empty($result[_sql_col("report_no")]) ? $result[_sql_col("report_no")] : "n/a";

	$item["description"] = _format_item_description(_format_param($item)); 

	return _format_param($item); 
}

function _format_results($old_results) {

	$prep = []; 

	foreach ($old_results as $result) {

		$item = _format_item($result);

		array_push($prep, $item); 
	}

	$results = []; 
	$results["count"] = sizeof($prep);
	$results["data"] = $prep; 


	return $results; 
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

function _respond($endpoint, $input) {
	$array = [];
	$array["error"] = False;
	$array["error_description"] = ""; 
	$array["message"] = False;
	$array["message_description"] = ""; 
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