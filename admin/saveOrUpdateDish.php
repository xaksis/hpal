<?php 
if(isset($_POST))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dish.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$dish = new Dish(); 
	$dm = new DishModel();
	$rm = new RestModel(); 
	$response = new ResponseObject(); 
	$restId = $_POST['rest_id'];
	if($_POST['rest_id']==0)
	{
		$restName = $response->sanitize($_POST['rest_name']); 
		$restResp = $rm->getRestaurantByName($restName, $restaurant);
		$restId = $restaurant['id'];
	}
	//add dish. if dish already exists, update rating
	$dish->name=$response->sanitize($_POST['name']); 
	$dish->restaurant_id=$restId;
	$dish->user_id=$_POST['user_id']; 
	$dish->rating=$_POST['rating'];  
 
	$response = $dm->createOrUpdateDish($dish); 
	
	//add dish review 
	$comment = $response->sanitize($_POST['comment']);
	$dm->createDishReview($response->code, $dish, $comment);
	
	echo $response->code;  
}
?>