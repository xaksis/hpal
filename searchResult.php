<?php
if(isset($_POST['type']))
{
	//sanitize input first!
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	$ro = new ResponseObject(); 
	$_POST['s_str'] = $ro->sanitize($_POST['s_str']); 
	$page = $_POST['page']; 
	$n = 6; 
	if($_POST['type'] == "places")
	{
		$start=0; 
		require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
		$response = new ResponseObject();
		$im = new imageModel(); 
		$rm = new RestModel(); 
		$rests = array(); 
		$response = $rm->getRestaurantsGivenSearchStr($_POST['s_str'], $page*$n, $n, $rests); 
		if(count($rests) == 0)
		{
		?>
			<div class="ui-widget">
				<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					Sorry! No match found for Places.</p>
				</div>
			</div>
		<?php
		}
		?>
		<div id="searchPaging">
			<a href="javascript: void(0);" id="restPrev" style="float: left; margin-left: 30px;" class="ui-state-default ui-corner-all">
				<span class="ui-icon ui-icon-triangle-1-w"></span>
			</a>
			<div style="float: left; text-align: center; width: 250px;">
				<?php echo "Page ",$page+1; ?>
			</div>
			<a href="javascript: void(0);" id="restNext" style="float: right; margin-right: 30px;" class="ui-state-default ui-corner-all">
				<span class="ui-icon ui-icon-triangle-1-e"></span>
			</a>
			<div id="clear">
			</div>
		</div>
		<?php
		$rc=0;
		foreach ($rests as $row) {
			$rc++; 
	?>
		<div class="result" style="width: 425px;" id="<?php if($rc%2){echo "resultEven";}else{echo "resultOdd";} ?>">
			 <div style="float: left; width: 10px; padding: 40px 0px 0px 5px; ">
				<?php echo $rc; ?>
			</div>
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
			<table style="float: left; width: 275px; margin: 5px 0px 0px 10px;" >
				<tr>
					
					<td ><span style="color: #a1723f; font-weight: bold;"><?php echo $row['name'];?></span></td>
					<td WIDTH="35%"><img src='<?php echo $rm->ratingInStar($row['adminRating']/$row['noOfRatings']); ?>' /></td>
				</tr>
				<tr>
					
					<td><?php echo $row['address']; ?> <br/> 
						<?php echo $row['city']; ?>, <?php echo $row['state']; ?> - 
						<?php echo $row['zipcode']; ?>
					</td>
					<td rowspan="2">
					</td>
				</tr>
				<tr>
					<td><?php echo $row['phone']; ?></td>
				</tr>
				<tr>
					<td colspan="2">
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
		
		<?php
	}
	else if($_POST['type'] == "dishes")
	{
		$start=0; 
		require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
		$im = new ImageModel(); 
		$dm = new DishModel();
	    $rm = new RestModel(); 
		$response = new ResponseObject();
		$dishes = array(); 
		$response = $dm->getSearchDishes($_POST['s_str'], $page*$n, $n, $dishes);
		if(count($dishes) == 0)
		{
		?>
			<div class="ui-widget">
				<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					Sorry! No match found for Dishes.</p>
				</div>
			</div>
		<?php
		}
		?>
		<div id="searchPaging">
			<a href="javascript: void(0);" id="dishPrev" style="float: left; margin-left: 30px;" class="ui-state-default ui-corner-all">
				<span class="ui-icon ui-icon-triangle-1-w"></span>
			</a>
			<div style="float: left; text-align: center; width: 250px;">
				<?php echo "Page ",$page+1; ?>
			</div>
			<a href="Javascript: void(0);" id="dishNext" style="float: right; margin-right: 30px;" class="ui-state-default ui-corner-all">
				<span class="ui-icon ui-icon-triangle-1-e"></span>
			</a>
			<div id="clear">
			</div>
		</div>
		<?php
		$rc=0;
		foreach ($dishes as $row) {
			$rc++; 
	?>
		<div class="result" style="width: 425px;" id="<?php if($rc%2){echo "resultEven";}else{echo "resultOdd";} ?>">
			 <div style="float: left; width: 10px; padding: 40px 0px 0px 5px; ">
				<?php echo $rc; ?>
			</div>
			<div class="thumbWrap">
				<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['restaurant_id']; ?>">
				<?php
					if($row['imagePath'] != 'NOT_SET'){
						echo "<img src='http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($row['imagePath'])."'/>"; 
					}
					else
					{
						echo "<img src='http://".$_SERVER['HTTP_HOST']."/hp/images/noRestaurantPic.png'/>"; 
					}
				?>
				</a>
			</div>
			<table style="float: left; width: 275px; margin: 5px 0px 0px 10px;" >
				<tr>
					
					<td><span style="color: #a1723f; font-weight: bold;"><?php echo $row['name'];?></span></td>
					<!-- dish rating should be averaged between all ratings CHANGE THIS LATER -->
					<td WIDTH="35%"><img src='<?php echo $rm->ratingInStar($row['rating']/$row['noOfRatings']); ?>' /></td>
				</tr>
				<tr>
					
					<td colspan="2">Found At:
						<?php
						$rests = array(); 
						$resp = new ResponseObject(); 
						$resp = $dm->getAllRestaurantsForDish($row['name'], $rests);
						//echo $resp->explanation; 
						$rc2=0;
						foreach($rests as $rest)
						{
							if($rc2>4)
								break; 
							$rc2++;
							echo '<a class="rest" href="http://',$_SERVER['HTTP_HOST'],'/hp/restProfile.php?restId=',$rest['id'],'">';
							echo $rest['name'],'</a>, ';		
						}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<?php
							$dresp = $dm->getDishDescription($row['id'], $desc);
							$descr = "";
							if($dresp->code == 0)
								$descr = "Not Available";
							else
								$descr = substr($desc['comment'], 0, 60); 
							echo "Desc: ", $descr, "..."; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						
					</td>
				</tr>
			</table>
			<div id="clear">
			</div>
		</div>
	<?php
		}
		
		?>
		
		<?php
	}
	?>
	<script type="text/javascript">
	$('#searchPaging').each(function(){
		$(this).find('a').hover(function(event) {
			$(this).toggleClass('ui-state-hover');
		});
	});
	
	$('#restNext').click(function(){
		<?php if($rc < $n){
			echo "return false;"; 
		}else{
		?>
		$("#sRests").load("searchResult.php", 
			{type: "places", 
			s_str: $("#searchTextEntry").val(), 
			page: <?php echo $page+1;?>});
		$('#searchMapWrapper').load("searchMap2.php", 
			{s_str: $("#searchTextEntry").val(), 
			page: <?php echo $page+1;?>});
		<?php } ?>
	});
	
	$('#restPrev').click(function(){
		<?php if($page==0){
			echo "return false;"; 
		}else{
		?>
		$("#sRests").load("searchResult.php", 
			{type: "places", 
			s_str: $("#searchTextEntry").val(), 
			page: <?php echo $page-1;?>});
		$('#searchMapWrapper').load("searchMap2.php", 
			{s_str: $("#searchTextEntry").val(), 
			page: <?php echo $page-1;?>});
		<?php } ?>
	});
	
	$('#dishNext').click(function(){
		<?php if($rc < $n){
			echo "return false;"; 
		}else{
		?>
		$("#sDishes").load("searchResult.php", 
			{type: "dishes", 
			s_str: $("#searchTextEntry").val(), 
			page: <?php echo $page+1;?>});
		<?php } ?>
	});
	
	$('#dishPrev').click(function(){
		<?php if($page == 0){
			echo "return false;"; 
		}else{
		?>
		$("#sDishes").load("searchResult.php", 
			{type: "dishes", 
			s_str: $("#searchTextEntry").val(), 
			page: <?php echo $page-1;?>});
		<?php } ?>
	});
	
	
	</script>
<?php
}
?>

