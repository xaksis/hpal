<script type="text/javascript">
$(document).ready(function(){
		
		$(".picHolder").hover(function () {
			// animate opacity to full
			$(this).children("#label").stop().animate({ opacity: 1 }, 'slow', 'swing');
		},
		// on mouse out
		function () {
			// animate opacity to nill
			$(this).children("#label").stop().animate({ opacity: 0 }, 'fast');
		});

        
 }); 
</script>
<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 


$gDishes = array();
$dm = new DishModel(); 

$response = $dm->getNewGdishes($_POST['page'], 1, $gDishes); 

$imageModel = new ImageModel(); 
$response = new ResponseObject(); 

?>

<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/dishProfile.php?gdId='.$gDishes[0]['id']; ?>">
<div id="restInfoStage" class="ui-widget-content ui-corner-all">
		<table style="margin: 15px 0px 0px 20px;">
			<tr>
				<td><span id="name"><?php echo $gDishes[0]['name']; ?></span></td>
			</tr>
			<tr>
				<td><?php 
						$comment = "Description not available"; 
						$comment = $gDishes[0]['description']; 
									
						$wrapped = wordwrap($comment, 120, "--split--"); 
						//echo $wrapped;
						$str_array = preg_split('[--split--]', $wrapped);
						echo $str_array[0], "...";
					?> 
				</td>
			</tr>
			<tr>
				<td colspan="2"><span class="rests" style="font-weight: bold;">Found At:</span>
						<?php
						$response = $dm->getDishesByGdishId(0, 3, $gDishes[0]['id'], $dishes); 
						$i=0;
						//uniquify the dishes
						 $uDishes = array();
						 foreach ($dishes as $row)
						 {
							array_push($uDishes, strtolower($row['dName']));
						 }
						 $dishes = array_unique($uDishes); 
						 $tc = 0; //total count of restaurants
						foreach ($dishes as $row) {
							//echo $row,"<br/>"; 
							$resp = new ResponseObject(); 
							$rests = array();
							$resp = $dm->getAllRestaurantsForDish($row, $rests);
							//echo $resp->explanation; 
							$rc2=0;
							foreach($rests as $rest)
							{ 
								$rc2++;
								$tc++; 
								if($tc>4)
									continue;
								echo '<a class="rest" href="http://',$_SERVER['HTTP_HOST'],'/hp/restProfile.php?restId=',$rest['id'],'">';
								echo $rest['name'],'</a>, ';
								
							}
						} 
						if($tc > 4)
								echo "...(".($tc-4)." more)"; 
						?>
						
						
					</td>
			</tr>
			<!--<tr>
				<td><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/dishProfile.php?gdId='.$gDishes[0]['id']; ?>&layout=standard&width=200&action=like&font=arial&colorscheme=light" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:30px" allowTransparency="true"></iframe></td>
			</tr>-->
		</table>
</div>

<?php
$dishes = array(); 
$im = new ImageModel();
$response = new ResponseObject();
$dm = new dishModel(); 
$im = new imageModel(); 

$response = $dm->getDishesByGdishId(0, 3, $gDishes[0]['id'], $dishes); 
$i=0;
$imgC=0; 
foreach ($dishes as $row) {
	
	//if(count($dishes)==6)
	//{
		$i++;
	?>
		<div class="picHolder" style="<?php if($i>1)echo "margin-top: 15px;"; ?> height: 190px;">
			<div id="pic">
				<img style="border: none;" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$row['imagePath']; ?>"/>
			</div>
			<div id="label">
				<span>Found At: <?php echo $row['rName'];?></span>
			</div>
		</div>
		<?php
		if($i==1)	{
		?>
			<div id="clear">
			</div>
		<?php
		}
}
	if($i<3)
	{
		$j=0;
		foreach ($dishes as $row)
		{
			//get each dish, and for each dish get list of images; 
			$images = array();  
			$resp = new ResponseObject();
			$resp = $im->getImagesForDish($row['dId'], $images);
			if(count($images) > 1)
			{
				array_splice($images, -1, 1); 
				foreach($images as $img)
				{
					$j++;
					?>
						<div class="picHolder" style="<?php if($i>1)echo "margin-top: 15px;"; ?> height: 190px;">
							<div id="pic">
								<img style="border: none;" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$img['imagePath']; ?>"/>
							</div>
							<div id="label">
								<span>Found At: <?php echo $row['rName'];?></span>
							</div>
						</div>
						<?php
					if($j >= 3-$i)
						break;
				}
			}
			if($j >= 3-$i)
				break;
		}
	
	}
	
?>

</a>
