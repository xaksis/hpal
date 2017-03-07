<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dish.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishDB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';

class mailModel{
	//function to create a new user
	public function send($from, $subject, $body)
	{
		$resp = new ResponseObject(); 
		
		if(mail($to, $subject, $body))
		{
			echo 'Success';
		}else{
			echo 'Failure'; 
		}
	}
}
?>