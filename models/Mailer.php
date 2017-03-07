<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/feedbackModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/ses.php';

class Mailer{
	//function to create a new user
	public function sendFeedback($subject, $body, $email)
	{
		session_start(); 
		$resp = new ResponseObject(); 
		//sanitize everything
		$subject = $resp->sanitize($subject);
		$body = $resp->sanitize($body);
		$email = $resp->sanitize($email);
		if(isset($_SESSION['id']))
			$from = $_SESSION['id'];
		else 
			$from = 0; 
		
		//save to db
		$fm = new FeedbackModel(); 
		
		$resp = $fm->addFeedback($from, $subject, $body);
		
		if($resp->code == 0)
		{
			$ses = new SimpleEmailService('AKIAIIASW3Y44KPTFOCQ', 'u5nVDvVBBYR4LHMZjH82aV5DkEJ9nA9v5BwaXVKl');
			$m = new SimpleEmailServiceMessage();
			$m->addTo('aks9800@gmail.com');
			//$m->addTo('sanskriti@haplette.com');
			//$m->addTo('clemens@haplette.com');
			$m->setFrom('info@haplette.com');
			$m->setSubject("New Feedback: ".$subject);
			$m->setMessageFromString($body." -".$email);
			$ses->sendEmail($m);
		}
		
		return $resp; 
	}
}
?>