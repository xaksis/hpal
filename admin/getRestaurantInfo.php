<?php
//required files
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

if(isset($_POST))
{
	$id = $_POST['rest_id']; 
	//create a restaurant object
	$restaurant = new Restaurant(); 
	//create a restModel object 
	$restModel = new RestModel(); 
	//create a response object 
	$response = new ResponseObject(); 
	
	$response = $restModel->getRestaurantById($id, $restaurant); 
	
	echo json_encode($restaurant); 
	
	/*
	echo "{ name: ".$restaurant->name.",
			address: ".$restaurant->address.",
			phone: ".$restaurant->phone.",
			genre: ".$restaurant->genre.",
			city: ".$restaurant->city.",
			state: ".$restaurant->state.",
			zipcode: ".$restaurant->zipcode.", 
			adminRating: ".$restaurant->adminRating." 
		}"; */
}

?>