<?php
	if(!isset($_POST['q_str']))
	{
		echo "Invalid Access!"; 
		return; 
	}
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	$dm = new DishModel(); 
	$rm = new RestModel(); 
	$response = new ResponseObject(); 
	$_POST['q_str'] = $response->sanitize($_POST['q_str']);
	$im = new imageModel(); 
	
	$rests = array(); 
	$response = $rm->getRestaurantsGivenSearchStr($_POST['q_str'], 0, 5, $rests);
	//$response = $dm->getTopRestaurantsForDish(5, $_POST['q_str'], $topRests); 
	if($response->code == 0)
	{
		echo $response->explanation; 
		return; 
	}
?>

<div id="pop_list" class="text ui-widget-content ui-corner-all" style="float: none; margin: 5px 0px 0px 5px; height: 520px;">
		<div id="restList" style="padding: 0px; margin-left: 10px;">
		<?php
			$rc=0;
			foreach ($rests as $row) {
				$rc++; 
		?>
			<div style="margin: 0px;" id="<?php if($rc%2)echo "odd";else echo "even"; ?>">
				 
				<div class="thumbWrap">
					<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['id']; ?>">
					<?php
						$images = array();
						$imgResp = new ResponseObject();
						$imgResp = $rm->getNonDishImageForRestaurant($row['id'], $images[0]);
						
						if($imgResp->code!=0)
						{
							echo "<img src='http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($images[0]['imagePath'])."'/>"; 
						}
						else
						{
							echo "<img src='http://".$_SERVER['HTTP_HOST']."/hp/images/noRestaurantPic.png'/>"; 
						}
					?>
					</a>
				</div>
				<table style="float: left; width: 190px; margin: 10px 0px 0px 10px;" >
					<tr>
						
						<td><a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['id']; ?>">
							<span style="color: #a1723f; font-weight: bold;"><?php echo $row['name'];?></span>
							</a>
						</td>
						<td></td>
						<td><img src='<?php echo $rm->ratingInStar($row['adminRating']/$row['noOfRatings']); ?>' /></td>
					</tr>
					<tr>
						
						<td colspan="3">
							<?php echo $row['address']; ?> <br/> 
						<?php echo $row['city']; ?>, <?php echo $row['state']; ?> - 
						<?php echo $row['zipcode']; ?>
						</td>
					</tr>
					<tr>
					
						<td colspan="3">
							<?php 	$categories = array(); 
							$rm->getCategoriesForRestaurant($row['id'], $categories);
							$count=0;
							foreach($categories as $category){
								if($count!=0)
									echo ", "; 
								echo "<a class='rest' href='http://".$_SERVER['HTTP_HOST'].'/hp/search.php?sstr='.$category['name']."'>";
								echo $category['name'];
								echo "</a>";
								$count++; 
							}							
							?>
						</td>
					</tr>
				</table>
				<div id="clear">
				</div>
			</div>
		<?php
			}
		?>
		</div>
	
	
</div>