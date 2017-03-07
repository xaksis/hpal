<?php 
if(isset($_POST['email']))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/userModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 
	
	$um = new UserModel(); 
	$response = $um->loginUser($_POST['email'], $_POST['password'], $user);
	$status = "passwordError";
	if($response->code ==1)
	{
		$status="OK";
	}
	echo "{ 'registered' : $status}"; 
}
?>