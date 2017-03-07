<?php 
if(isset($_GET['term']))
{
	$start=0; 
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$dm = new DishModel(); 
	$response = new ResponseObject();
	$_GET['term'] = $response->sanitize($_GET['term']);
	$_GET['rest'] = $response->sanitize($_GET['rest']);
	$dishes = array(); 
	$response = $dm->getAutoCompleteDishesForRestaurantName($_GET['rest'], $_GET['term'], $start, 50, $dishes); 
	echo "["; 
	$rc = 0; 
	foreach($dishes as $dish)
	{
		if($rc!=0)
			echo ","; 
		echo  "\"".htmlspecialchars_decode($dish['name'], ENT_QUOTES)."\"";  
		$rc++; 
	}
	echo "]"; 
}
?>