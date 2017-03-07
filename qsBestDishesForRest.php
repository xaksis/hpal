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
	
	$topDishes = array(); 
	$response = $dm->getDishesForRestaurantName($_POST['q_str'],0,5, $topDishes);
	if($response->code == 0)
	{?>
		<div class="ui-widget" style="width: 340px;">
			<div class="ui-state-highlight ui-corner-all" style="margin: 30px; padding: 0 .7em;"> 
				<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
				Sorry! Dishes have not been rated for this restaurant.<br/>
				</a>
				</p>
				
				
				
			</div>
		</div> 
		
		
		<?php
		return; 
	}
?>

<div id="pop_list" class="text ui-widget-content ui-corner-all" style="float: none; margin: 5px 0px 0px 10px; height: 520px;">
		<div id="restList" style="padding: 0px; margin-left:10px;">
		<?php
			$rc=0;
			foreach ($topDishes as $row) {
				$rc++; 
		?>
			<div style="margin: 0px;" id="<?php if($rc%2)echo "odd";else echo "even"; ?>">
				 
				<div class="thumbWrap">
					<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['rid']; ?>">
					
					<?php
						
						 echo "<img src='http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($row['imagePath'])."'/>"; 
					?>
					</a>
				</div>
				<table style="float: left; width: 190px; margin: 10px 0px 0px 10px;" >
					<tr>
						
						<td><a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['rid']; ?>">
							<span style="color: #a1723f; font-weight: bold;"><?php echo $row['name'];?></span>
							</a>
						</td>
						<td></td>
						<td><img src='<?php echo $rm->ratingInStar($row['rating']/$row['noOfRatings']); ?>' /></td>
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