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
