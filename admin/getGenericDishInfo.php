<?php
//required files
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

if(isset($_POST))
{
	$id = $_POST['gdish_id'];  
	//create a restModel object 
	$dm = new DishModel(); 
	//create a response object 
	$response = new ResponseObject(); 
	$row = null; 
	$response = $dm->getGenericDishById($id, $row); 
	
	echo json_encode($row); 
	
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