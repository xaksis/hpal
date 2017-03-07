<?php 
//add restaurant
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

//collect all post variables
	if(isset($_POST))
	{
		//create a restaurant object
		$restaurant = new Restaurant(); 
		
		$restId =    	   		   $_POST['id'];
		$restaurant->name =    	   $_POST['name'];
		$restaurant->address = 	   $_POST['address'];
		$restaurant->phone =   	   $_POST['phone'];
		$restaurant->genre =   	   $_POST['genre'];
		$restaurant->city =    	   $_POST['city'];
		$restaurant->state =       $_POST['state'];
		$restaurant->zipcode =     $_POST['zip'];
		$restaurant->lat	 =	   $_POST['lat'];
		$restaurant->lon	 =	   $_POST['lon'];
		$restaurant->adminRating = $_POST['adminRating'];
		
		//create a restModel object 
		$restModel = new RestModel(); 
		
		$response = new ResponseObject(); 
		$response = $restModel->updateRest($restId, $restaurant); 
		
		echo $response->explanation; 
	}


?>