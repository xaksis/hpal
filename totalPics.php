<?php
if(isset($_POST))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$im = new ImageModel();
	$response = new ResponseObject(); 
	$response = $im->getNumberOfImagesForRest($_POST['rest_id']);

	echo $response->code; 
}
?>