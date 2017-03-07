<?php
if(isset($_POST))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'hp/models/imageModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'hp/models/responseObject.php';
	
	$dm = new DishModel();
	$response = new ResponseObject(); 
	$rm = new RestModel(); 
	$im = new ImageModel(); 
	
	$dishes= array(); 
	//get a list of restaurants given a generic dish
	//to do this, we first get a list of dishes for each generic dish, and
	//for each dish we get the restaurant associated with it. 
	$response = $dm->getDishesByGdishId(0, 5, $_POST['gd_id'], $dishes);

	$i=0;
	foreach ($dishes as $row) {
		$resp = new ResponseObject();
		$imgResp = new ResponseObject();
		$restaurant = new Restaurant();
		$resp = $rm->getRestaurantById($row['restaurant_id'], $restaurant); 
		$imgResp = $rm->getNonDishImageForRestaurant($row['restaurant_id'], $image); 
	?>
		<div class="comment" id="<?php if($i%2){echo "commentEven";}else{echo "commentOdd";} ?>">
			<div class="thumbWrap">
				<a href="javascript: return false;">
				<?php
					if(isset($row['imagePath']))
					{
						echo "<img src='http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($image['imagePath'])."'/>"; 
					}
					else
					{
						echo "<img src='http://".$_SERVER['HTTP_HOST']."/hp/images/noRestaurantPic.png'/>"; 
					}
				?>
				</a>
			</div>
			<div id="userComment">
					<table style="float: left; width: 190px;" >
						<tr>
							
							<td><span style="color: #a1723f; font-weight: bold;"><?php echo $restaurant->name;?></span></td>
							<td></td>
							<td><img src='<?php echo $rm->ratingInStar($restaurant->adminRating); ?>' /></td>
						</tr>
						<tr>
							
							<td colspan="3"><?php echo $restaurant->address; ?> <br/> 
								<?php echo $restaurant->city; ?>, <?php echo $restaurant->state; ?> - 
								<?php echo $restaurant->zipcode; ?>
							</td>
						</tr>
						<tr>
							<td colspan="3"><?php echo $restaurant->phone; ?></td>
						</tr>
						<tr>
							<td colspan="3">
								<?php 	$categories = array(); 
									$rm->getCategoriesForRestaurant($row['restaurant_id'], $categories);
									$count=0;
									foreach($categories as $category){
										if($count!=0)
											echo ", "; 
										echo $category['name'];
										$count++; 
									}							
								?>
							</td>
						</tr>
					</table>
					
			</div>
		</div>
	<?php
		$i++; 
	}
}
?>