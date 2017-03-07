<?php 
if(isset($_POST))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	 
	$im = new imageModel();
	$rm = new RestModel();
	$response = new ResponseObject(); 
	
	//add dish. if dish already exists, update rating
	$caption=$response->sanitize($_POST['caption']); 
	$id=$_POST['img_id'];
	$dishId=$_POST['dish_id'];
	$restName = $response->sanitize($_POST['rest_name']); 
	$restResp = $rm->getRestaurantByName($restName, $restaurant);
	$restId = $restaurant['id'];
 
 
	$response = $im->updateImageCaption($id, $restId, $caption, $dishId); 
	
	//add image caption
	echo $response->explanation;  
}
?>