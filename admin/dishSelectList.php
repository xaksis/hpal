<?php
if(isset($_POST['restId']))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$im = new DishModel();
	$response = new ResponseObject(); 
	$dishes= array(); 
	$response = $im->getDishesForRestaurant($_POST['restId'],0,100, $dishes);
	if($response->code == 0)
	{
		echo $response->explanation; 
		return; 
	}
	echo "<select id='dishList' size='13' style='width: 170px;'>";
	foreach ($dishes as $row) {
		echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>"; 	
	}
	echo "</select>";
}
?>