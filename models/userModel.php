<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/db_interface.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';

class UserModel{
	//function to create a new user
	public function createUser($user)
	{
		$db = new Db(); //create a DB class
		$response = new ResponseObject(); //a response object
		
		$response = $db->insert_user($user);
		return $response;  
	}
	
	//function to create a new user (federated)
	public function createUserNew($user)
	{
		$db = new Db(); //create a DB class
		$response = new ResponseObject(); //a response object
		
		$response = $db->insert_user_new($user);
		return $response;  
	}
	
	//function to set a user property
	
	
	//function to get a user property
	
	//function to delete user
	public function deleteUser($email)
	{
		$db = new Db();
		$response = $db->delete_user($email);
		if($response == "Success")
			echo "User successfully deleted.";
		else
			echo "Error: ".$response;
	}
	
	//function to check if user exists
	public function loginUser($username, $password, &$user)
	{
		$db = new Db(); //create a DB class
		$response = new ResponseObject(); //a response object
		
		$response = $db->login_user($username, $password, $user);
		return $response; 
	}
	
	//function to get user status. 0 if unregistered. 1 if registered. 
	public function userStatus($email, &$user)
	{
		$db = new Db(); //create a DB class
		$response = new ResponseObject(); //a response object
		
		$response = $db->get_user_status($email, $user);
		return $response; 
	}
	
	public function upgradeToFederated($email)
	{
		$db = new Db(); //create a DB class
		$response = new ResponseObject(); //a response object
		
		$response = $db->upgrade_to_federated($email);
		return $response;
	}
}
?>