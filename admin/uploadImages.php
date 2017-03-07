<?php
include('imageProc.php');
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 
session_start();

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

//collect all post variables
if(isset($_POST) && isset($_FILES))
{
	$file = $_FILES['file'];
	$imgProcessor = new ImageProc();
	$imgProcessor->load($file['tmp_name']);
	//uniqify the name man
	$filename = microtime_float(); 
	//save normal
	$imgProcessor->save($filename.'_orig.jpg');
	//resize normal
	$imgProcessor->resize(223,149);
	$imgProcessor->save($filename.'.jpg');
	//resize thumb
	$imgProcessor->resize(99,67);
	$imgProcessor->save($filename.'_thumb.jpg');
    
	//create an image object
	$imageObj = new image(); 
	$imageObj->user_id = $_SESSION['id']; 
	$imageObj->restaurant_id = $_POST['rest_id'];
    $imageObj->path_to_thumb =  $filename.'_thumb.jpg'; 
	$imageObj->path_to_orig = $filename.'_orig.jpg';
	$imageObj->path_to_reg = $filename.'.jpg';
	
    //create an imageModel object 
	$imgModel = new ImageModel(); 
	
	$response = new ResponseObject(); 
	$response = $imgModel->saveImage($imageObj); 

	echo '{"name":"'.$file['name'].'","type":"'.$file['type'].'","size":"'.$file['size'].'"}';
}
?>