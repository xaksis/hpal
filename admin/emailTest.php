<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/ses.php';
	$response = new ResponseObject(); 
	$ses = new SimpleEmailService('AKIAIIASW3Y44KPTFOCQ', 'u5nVDvVBBYR4LHMZjH82aV5DkEJ9nA9v5BwaXVKl');
	
	//print_r($ses->verifyEmailAddress('info@haplette.com'));
	
	print_r($ses->listVerifiedEmailAddresses());
	
	$m = new SimpleEmailServiceMessage();
	$m->addTo('akshay@haplette.com');
	//$m->addTo('sanskriti@haplette.com');
	//$m->addTo('clemens@haplette.com');
	$m->setFrom('info@haplette.com');
	$m->setSubject('First Test!');
	$m->setMessageFromString('If this works, i will be very happy!');

	print_r($ses->sendEmail($m));

?>