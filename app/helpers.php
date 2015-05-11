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
	$password = 'loplop34'; 

	$db = new PDO($dsn, $username, $password, $options);

	return $db; 
}

function _escape_mysql($string) {

	$string = str_replace("'", "''", $string);
	$string = str_replace("\\", "\\\\", $string);

	return $string; 
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