<?php 
if(isset($_POST))
{
	$selectId = $_POST['selectId']; 
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	$rm = new RestModel(); 
	$response = new ResponseObject(); 
	//get subcategories
	$cuisines = array();
	$response = $rm->getCuisinesForSubcat($cuisines, $_POST['subcat']); 
?>
<select class="cuisine_sel" id="<?php echo $selectId;?>">
	<option value='0'>Select One</option>
	<?php
		foreach ($cuisines as $row) {
			echo "<option value='".$row['id']."'>".$row['name']."</option>"; 
		}
	?>
</select>
<?php
}
?>
