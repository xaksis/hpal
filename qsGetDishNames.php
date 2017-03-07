<?php 
if(isset($_GET['term']))
{
	$start=0; 
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$dm = new DishModel(); 
	$response = new ResponseObject();
	$_GET['term'] = $response->sanitize($_GET['term']);
	$names = array(); 
	$response = $dm->getAutocompleteNames($_GET['term'], $start, 30, $names); 
	echo "["; 
	$rc = 0; 
	foreach($names as $name)
	{
		if($rc!=0)
			echo ","; 
		echo  "\"".trim($name['name'])."\"";  
		$rc++; 
	}
	echo "]"; 
	//echo json_encode($names); 
}

?>