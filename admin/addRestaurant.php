<?php 
//add restaurant
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

//collect all post variables
	if(isset($_POST))
	{
		$response = new ResponseObject();
		//create a restaurant object
		$restaurant = new Restaurant(); 
		
		$restaurant->name =    	   $response->sanitize($_POST['name']);
		$restaurant->address = 	   $response->sanitize($_POST['address']);
		$restaurant->phone =   	   $_POST['phone'];
		//$restaurant->genre =   	   $_POST['genre'];
		$restaurant->city =    	   $response->sanitize($_POST['city']);
		$restaurant->state =       $_POST['state'];
		$restaurant->zipcode =     $_POST['zip'];
		if($_POST['adminRating'] != "")
			$restaurant->adminRating = $_POST['adminRating'];
		$restaurant->lat 		 = $_POST['lat'];
		$restaurant->lon		 = $_POST['lon'];
		//create a restModel object 
		$restModel = new RestModel(); 
		
		 
		$response = $restModel->createRest($restaurant); 
		
		echo $response->code; 
		
	}


?>