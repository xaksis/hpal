<?php
//required files
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

if(isset($_POST))
{
	//create a restModel object 
	$dm = new DishModel(); 
	//create a response object 
	$response = new ResponseObject();
	
	if(isset($_POST['gd_id']))
	{
		$response = $dm->addGenericToDish($_POST['d_id'], $_POST['gd_id']);
		echo $response->explanation;  
	}
	
	
	
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