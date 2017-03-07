<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageDB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

class ImageModel{

	//function to save a new image
	public function saveImage($image)
	{
		$db = new ImageDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->insert_image($image);
		
		return $response; 
	}
	
	//function to get unconfirmed images for a user
	public function getUnconfirmed($userId, &$pictures)
	{
		$db = new ImageDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_unconfirmed_images_for_user($userId, $pictures); 
		
		return $response; 
	}
	
	
	//function to update image caption
	public function updateImageCaption($id, $restId, $caption, $dishId)
	{
		$db = new ImageDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->update_image_caption($id, $restId, $caption, $dishId);
		
		return $response; 
	}
	
	//function to update image caption
	public function deleteImage($id)
	{
		$db = new ImageDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->delete_image($id);
		
		return $response; 
	}
	
	//function to get images by restaurant
	public function getRecentImagesForRestaurant($restId, $start, $n, &$images)
	{
		$db = new ImageDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_n_recent_images_by_rest_id($restId, $start, $n, $images);
		
		return $response;
	}
	
	//function to get images by dish
	
	
	//function to get total number of images given rest id
	public function getNumberOfImagesForRest($restId)
	{
		$db = new ImageDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_image_count_for_rest($restId);
		
		return $response;
	}
	
	//function to get images for a given dish
	public function getImagesForDish($dishId, &$images)
	{
		$db = new ImageDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_images_for_dish($dishId, $images);
		
		return $response;
	}
	
	//function to get thumb path given image path
	public function getThumbPath($imagePath)
	{ 
		$filename = substr($imagePath, 0, strrpos($imagePath, "."));
		$filename = $filename."_thumb".substr($imagePath, strrpos($imagePath, "."));
		return $filename; 
	}
	
}
?>