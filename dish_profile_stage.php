
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

$dishes = array(); 
$im = new ImageModel();
$response = new ResponseObject();
$dm = new dishModel(); 
$im = new imageModel(); 

$response = $dm->getDishesByGdishId(0, 6, $_POST['gd_id'], $dishes); 
$i=0;
$imgC=0; 
foreach ($dishes as $row) {
	
	//if(count($dishes)==6)
	//{
		$i++;
	?>
		<a href="#">
		<div class="picHolder" style="<?php if($i>3)echo "margin-top: 25px;"; ?>">
			<div id="pic">
				<img style="border: none;" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$row['imagePath']; ?>"/>
			</div>
			<div id="label">
				<span>Found At: <?php echo $row['rName'];?></span>
			</div>
		</div>
		</a>
		<?php
		if($i==3)	{
		?>
			<div id="clear">
			</div>
		<?php
		}
}
	if($i<6)
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
						<a href="#">
						<div class="picHolder" style="<?php if($i>3)echo "margin-top: 25px;"; ?>">
							<div id="pic">
								<img style="border: none;" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$img['imagePath']; ?>"/>
							</div>
							<div id="label">
								<span>Found At: <?php echo $row['rName'];?></span>
							</div>
						</div>
						</a>
						<?php
						if($j==3)	{
						?>
							<div id="clear">
							</div>
						<?php
						}
					if($j >= 6-$i)
						break;
				}
			}
			if($j >= 6-$i)
				break;
		}
	
	}
?>

