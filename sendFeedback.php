<?php 
if(isset($_POST['comment']))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/feedbackModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/Mailer.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	$resp = new ResponseObject(); 
	$mailer = new Mailer(); 
	//sanitation will happen in this function
	$resp = $mailer->sendFeedback($_POST['subject'], $_POST['comment'], $_POST['email']); 
	
	echo $resp->explanation; 
}
?>