<?php 
if(isset($_POST['name']))
{
	session_start();
	session_destroy(); 
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/userModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 
	
	$user = array("displayName" => $_POST['fName'],
					"fullName"  => $_POST['name'],
				  "firstName" => $_POST['fName'],
				   "lastName" => $_POST['lName'],
				   "email" => $_POST['id']); 
	
	$um = new UserModel();
	
	$response = $um->createUserNew($user); 
	
	$_SESSION['id'] = $response->code;
		$_SESSION['email'] = $user['email']; 
		$_SESSION['displayName'] = $user['displayName'];
		$_SESSION['privilege'] = 1; 
		$_SESSION['fullName'] = $user['fullName'];
		$_SESSION['fb'] = true; 
}
?>