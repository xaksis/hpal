
<script type="text/javascript">
$(document).ready(function(){
		
		$(".picHolder").hover(function () {
			// animate opacity to full
			$(this).children("#label").stop().animate({ top: 15, opacity: 1 }, 'medium', 'swing');
		},
		// on mouse out
		function () {
			// animate opacity to nill
			$(this).children("#label").stop().animate({ top: 0, opacity: 0 }, 'fast');
		});

        
 });         
</script>

<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';

$images = array(); 
$im = new ImageModel();
$response = new ResponseObject(); 

$response = $im->getRecentImagesForRestaurant($_POST['rest_id'], $_POST['page'], 4, $images); 
$i=0;

foreach ($images as $row) {
$isDish = ($row['dish_id'] != 0); 
$hasGDish = false; 
$dRow; 
if($isDish)
{
	//get dish from dish id first
	$dm = new DishModel(); 
	$dResp = new ResponseObject(); 
	$dResp = $dm->getDishById($row['dish_id'], $dRow);
	if($dRow['gDish_id'] != null)
		$hasGDish = true; 
}
?>
    <div class="picHolder" style="">
    	<div id="pic">
			<?php if($hasGDish) {?>
			<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/dishProfile.php?gdId='.$dRow['gDish_id']; ?>">
			<?php } ?>	
				<img style="border: none;" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$row['imagePath']; ?>"/>
			<?php if($hasGDish) {?>
			</a>
			<?php } ?>
		</div>
        <div id="label">
        	<span><?php echo $row['caption'];?></span>
        </div>
	</div>
    	<div id="clear">
        </div>
    <?php
	$i++; 
}
?>
