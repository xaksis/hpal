<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	include 'necessities.php';
	$page = 'favorite';
?>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<title>Grub Pal - Favorites</title>
<script type="text/javascript">

$(document).ready(function(){
	
	
	
	$('#profile_stage').load('<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/archives/Aug_week_1.php'; ?>');
	
	var loaded = 1; 
	
	$('#left_button_link').click(function(){
		if(loaded==0)
		{
			loaded = 1; 
			$('#profile_stage').fadeOut("slow", function(){
				$('#profile_stage').load('<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/archives/Aug_week_1.php'; ?>', 
					function(){
						$('#profile_stage').fadeIn("slow"); 
					});
			});
		}
		else
		{
			loaded = 0; 
			$('#profile_stage').fadeOut("slow", function(){
				$('#profile_stage').load('<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/archives/Aug_week_3.php'; ?>', 
					function(){
						$('#profile_stage').fadeIn("slow"); 
					});
			});
		}
	});
	
	$('#right_button_link').click(function(){
		if(loaded==0)
		{
			loaded = 1; 
			$('#profile_stage').fadeOut("slow", function(){
				$('#profile_stage').load('<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/archives/Aug_week_1.php'; ?>', 
					function(){
						$('#profile_stage').fadeIn("slow"); 
					});
			});
		}
		else
		{
			loaded = 0; 
			$('#profile_stage').fadeOut("slow", function(){
				$('#profile_stage').load('<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/archives/Aug_week_3.php'; ?>', 
					function(){
						$('#profile_stage').fadeIn("slow"); 
					});
			});
		}
	});
	
 });      

</script>


</head>

<body>
	<div id="outerWrapper">
		<div id="header">
        	<?php include 'header.php' ?>
        </div>
        
        <div id="content">
        	<div id="stage_blank">
				<div id="nav_button">
                	<a id="left_button_link" href="#" onclick="return false;">
                    	<span>
                        </span>
                    </a>
                </div>
				
				<div id="profileStageWrapper">
					<div id="profile_stage">
					</div>
				</div>
				
				<div id="nav_button">
                	<a id="right_button_link" href="#" onclick="return false;">
                    	<span>
                        </span>
                    </a>
                </div>
            </div><!--STAGE ENDS HERE-->
            
			<?php
				require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
				require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
				require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
				$rm = new RestModel(); 
				$response = new ResponseObject(); 
				$im = new imageModel(); 
				
				$topRests = array(); 
				$response = $rm->getTopRestaurants(5,$topRests); 
			?>
			
            <div id="pop_list">
            	<div id="resttitle">
					<span>Popular Places</span>
                </div>	
                   
                    <div id="restList" class="text ui-widget-content ui-corner-all">
                	<?php
						$rc=0;
						foreach ($topRests as $row) {
							$rc++; 
					?>
                    	<div id="<?php if($rc%2)echo "odd";else echo "even"; ?>">
							 
							<div class="thumbWrap">
								<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['id']; ?>">
								<?php
									$image = new stdClass();
									$imgResp = new ResponseObject();
									$imgResp = $rm->getNonDishImageForRestaurant($row['id'], $image); 
									if($imgResp->code!=0)
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
							<table style="float: left; width: 190px; margin: 10px 0px 0px 10px;" >
								<tr> 
									
									<td><span style="color: #a1723f; font-weight: bold;"><?php echo $row['name'];?></span></td>
									<td></td>
									<td><img style="float: right; margin-right:5px;" src='<?php echo $rm->ratingInStar($row['adminRating']/$row['noOfRatings']); ?>' /></td>
								</tr>
								<tr>
									
									<td colspan="3"><?php echo $row['address']; ?> <br/> 
										<?php echo $row['city']; ?>, <?php echo $row['state']; ?> - 
										<?php echo $row['zipcode']; ?>
									</td>
								</tr>
								<tr>
									<td colspan="3"><?php echo $row['phone']; ?></td>
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
            
            <div id="pop_list">
				<?php
					require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
					$dm = new DishModel(); 
					$topDishes = array(); 
					$response = $dm->getTopDishes(5, $topDishes); 
					
					
				?>
            	<div id="dishtitle">
					<span>Popular Dishes</span>
                </div>
                <div id="dishList" class="text ui-widget-content ui-corner-all">
                	<?php
						$rc=0;
						foreach ($topDishes as $row) {
							$rc++;
					?>
                    	<div id="<?php if($rc%2)echo "odd";else echo "even"; ?>">
							<div class="thumbWrap">
								<?php if($row['gdid'] != null){?>
								<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/dishProfile.php?gdId='.$row['gdid']; ?>">
								<?php } else {?>
								<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['rid']; ?>">
								<?php }?>
								
								<?php
								
									if(isset($row['imagePath']))
									{
										echo "<img src='http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($row['imagePath'])."'/>"; 
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
									
									<td><span style="color: #a1723f; font-weight: bold;"><?php echo $row['dname'];?></span></td>
									<td></td>
									<td><img style="float: right; margin-right:5px;" src='<?php echo $rm->ratingInStar($row['rating']/$row['noOfRatings']); ?>' /></td>
								</tr>
								<tr>
									
									<td colspan="3">Found At: 
										<a class='rest' href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['rid']; ?>">
										<?php echo $row['rname']; ?> 
										</a>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<?php if($row['description'] != 'NOT_SET')
												echo $row['description']; 
											  else if(isset($row['comment']))
												echo $row['comment'];
											  else
												echo "Description has not been added yet."; 
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
            
            
            <div id="pick_list_wrapper">
            	<div id="pick_title">
                </div>
                <div id="pick_list">
                	<h3>Hot Pot</h3>
                    <ol>
                    	<li><a>Ice Fire Land</a></li>
                        <li><a>Little Lamb</a></li>
                        <li><a>Quickly's shabu shabu</a></li>
                    </ol>
                </div>
                
            </div>
            
            
        </div>
        
        <div id="footer">
			<?php include 'footer.php'; ?>
        </div>
	</div>

</body>
</html>
