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

function _format_results($old_results) {

	$prep = []; 

	foreach ($old_results as $result) {

		$item = []; 

		$item["shape"] = !empty($result["Shape"]) ? $result["Shape"] : "n/a";
		$item["cut"] = !empty($result["cut grade"]) ? $result["cut grade"] : "n/a";
		$item["clarity_val"] = !empty($result["clarity code"]) ? $result["clarity code"] : "n/a";
		$item["polish"] = !empty($result["polish"]) ? $result["polish"] : "n/a";
		$item["symmetry"] = !empty($result["symmetry code"]) ? $result["symmetry code"] : "n/a";
		$item["fluorescence"] = !empty($result["fluorescence"]) ? $result["fluorescence"] : "n/a";
		$item["carats"] = !empty($result["size for website rounded"]) ? floatval($result["size for website rounded"]) : "n/a";
		$item["clarity"] = !empty($result["grade non cert clarity"]) ? $result["grade non cert clarity"] : "n/a";
		$item["price"] = !empty($result["Total Parcel"]) ? floatval($result["Total Parcel"]) : "n/a";
		$item["depth"] = !empty($result["depth"]) ? floatval($result["depth"]) : "n/a";
		$item["table"] = !empty($result["table"]) ? floatval($result["table"]) : "n/a";

		array_push($prep, $item); 
	}

	$results = []; 
	$results["count"] = count($prep);
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