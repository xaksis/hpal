<?php 
if(isset($_POST))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	 
	$im = new imageModel();
	$response = new ResponseObject(); 
	
	//delete image
	$id=$_POST['img_id'];
 
	$response = $im->deleteImage($id); 

	echo $response->explanation;  
}
?>