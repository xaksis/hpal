<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/feedbackDB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

class FeedbackModel{

	//function to save a new image
	public function addFeedback($userId, $subject, $comment)
	{
		$db = new FeedbackDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->insert_feedback($userId, $subject, $comment);
		return $response; 
	}
	
	//function to get unconfirmed images for a user
	public function getFeedbacks($start, $n, &$feedbacks)
	{
		$db = new FeedbackDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_feedbacks($start, $n, $feedbacks); 
		return $response; 
	}
}
?>