<?php 
//add restaurant
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/user.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/userModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

//collect all post variables
	if(isset($_POST))
	{
		
		//create a restaurant object
		$user = new User(); 
		
		$user->username =    	   $_POST['user'];
		$user->password =    	   sha1($_POST['pass']);
		$user->privilege =    	   1; 
		
		//create a restModel object 
		$userModel = new UserModel(); 
		
		$response = new ResponseObject(); 
		$response = $userModel->createUser($user); 
		
		echo $response->explanation; 
	}


?>