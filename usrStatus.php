<?php 
if(isset($_POST['email']))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/userModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 
	
	$um = new UserModel(); 
	$response = $um->userStatus($_POST['email']);
	echo "{ 'registered': $response->code}";
}
?>