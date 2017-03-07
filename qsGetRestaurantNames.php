<?php 
if(isset($_GET['term']))
{
	$start=0; 
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$rm = new RestModel(); 
	$response = new ResponseObject();
	$_GET['term'] = $response->sanitize($_GET['term']);
	$rests = array(); 
	$response = $rm->getRestaurantNames($_GET['term'], $start, 50, $rests); 
	echo "["; 
	$rc = 0; 
	foreach($rests as $rest)
	{
		if($rc!=0)
			echo ","; 
		echo  "\"".htmlspecialchars_decode($rest['name'], ENT_QUOTES)."\"";  
		$rc++; 
	}
	echo "]"; 
}
?>