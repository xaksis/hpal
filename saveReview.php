<?php 
if(isset($_POST['restId']))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 
	
	$rm = new RestModel(); 
	$response = new ResponseObject(); 
	session_start(); 
	$title = $response->sanitize($_POST['title']); 
	$comment = $response->sanitize($_POST['comment']);
	if($comment)
	{
		$response = $rm->addRestaurantReview($_POST['restId'], $_SESSION['id'], $title, $comment, $_POST['rating']); 
		echo $response->explanation;
	}
	else
	{
		echo "Please enter a comment!"; 
	}
}
?>