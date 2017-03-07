
<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 


$rests = array();
$rm = new RestModel(); 

$response = $rm->getNewRestaurants($_POST['page'], 1, $rests); 

$imageModel = new ImageModel(); 
$response = new ResponseObject(); 

$restLink = "<a href='http://".$_SERVER['HTTP_HOST']."/hp/restProfile.php?restId=".$rests[0]['id']."'>"; 

?>

<div id="restInfoStage" class="ui-widget-content ui-corner-all">
		<table style="margin: 15px 0px 0px 20px; width: 200px;">
			<tr>
				<td><?php echo $restLink; ?><span id="name"><?php echo $rests[0]['name']; ?></span></a></td>
			</tr>
			<tr>
				<td ><?php echo $rests[0]['address']; ?> <img style="float: right;" src='<?php echo $rm->oRatingInStar($rests[0]['adminRating']/$rests[0]['noOfRatings']); ?>' /><br/> 
				    <?php echo $rests[0]['city']; ?>, <?php echo $rests[0]['state']; ?> - 
					<?php echo $rests[0]['zipcode']; ?>
				</td>
			</tr>
			<tr>
				<td ><?php echo $rests[0]['phone']; ?></td>
			</tr>
			<tr>
				
			</tr>
			<tr>
				<td ><?php 	$categories = array(); 
							$rm->getCategoriesForRestaurant($rests[0]['id'], $categories);
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
			<tr>
				<!--<td><?php //$response->likeButton('http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$rests[0]['id']); ?></td>-->
				<td><!--<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile?restId='.$rests[0]['id']; ?>&layout=standard&width=200&action=like&font=arial&colorscheme=light" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:30px" allowTransparency="true"></iframe>--></td>
			</tr>
		</table>
</div>
<?php

$images = array(); 
$im = new ImageModel();
$response = $im->getRecentImagesForRestaurant($rests[0]['id'], 0, 3, $images); 
$i=0;
foreach ($images as $row) {

?>
	<div class="picHolder" style="<?php if($i>2)echo "margin-top: 15px;" ?> height: 195px;">
		<?php echo $restLink; ?>
    	<div id="pic" style="">
        	<img style="border: none;" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$row['imagePath']; ?>"/>
        </div>
        <div id="label" style="margin-top: 6px;">
        	<span><?php echo $row['caption'];?></span>
        </div>
		</a>
	</div>
	<?php
	if($i==0)	{
	?>
    	<div id="clear">
        </div>
<?php
	}
$i++; 
}
?>
<script type="text/javascript">
$(document).ready(function(){
		
		$(".picHolder").hover(function () {
			// animate opacity to full
			$(this).find("#label").stop().animate({ opacity: 1 }, 'slow', 'swing');
		},
		// on mouse out
		function () {
			// animate opacity to nill
			$(this).find("#label").stop().animate({ opacity: 0 }, 'fast');
		});

        
 }); 
</script>