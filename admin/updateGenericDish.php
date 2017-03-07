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
	
	if(isset($_POST['gd_name']))
	{
		$response = $dm->insertGenericDish($_POST['gd_name'], $_POST['gd_desc'], $_POST['family']);
		echo $response->code;
	}
	else
	{
		$response = $dm->updateGenericDish($_POST['gd_id'], $_POST['gd_desc'], $_POST['family']);
		echo $_POST['gd_id']; 
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