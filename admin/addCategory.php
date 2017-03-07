<?php
	if(isset($_POST))
	{
		require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
		$restId = $_POST['rest_id'];
		$catId = $_POST['cat_id'];
		
		$rm = new RestModel(); 
		$response = new ResponseObject(); 
		
		$response = $rm->addCategoryToRestaurant($restId, $catId); 
		
		echo $response->explanation; 
	}
?>