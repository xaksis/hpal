<?php
if(isset($_POST))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$dm = new DishModel();
	$response = new ResponseObject(); 
	$rm = new RestModel();
	$im = new ImageModel();
	$dishes= array(); 
	$n=5;
	$page = $_POST['page'];
	$response = $dm->getDishesForRestaurant($_POST['restID'],$page*$n,$n, $dishes);
	
	 
?>
<div id="paging">
		<a href="javascript: void(0);" id="dishPrev" style="float: left; margin-left: 30px;" class="ui-state-default ui-corner-all">
			<span class="ui-icon ui-icon-triangle-1-w"></span>
		</a>
		<div style="float: left; margin-top:5px; text-align: center; width: 50%;">
			<?php echo "Page ",$page+1; ?>
		</div>
		<a href="Javascript: void(0);" id="dishNext" style="float: right; margin-right: 30px;" class="ui-state-default ui-corner-all">
			<span class="ui-icon ui-icon-triangle-1-e"></span>
		</a>
		<div id="clear">
		</div>
</div>
<?php
	$i=0;
	foreach ($dishes as $row) {
	?>
		<div class="comment" style="width: 280px; margin-left: 5px;" id="<?php if($i%2){echo "commentEven";}else{echo "commentOdd";} ?>">
			<div class="thumbWrap">
				<?php if($row['gid'] == null) {?>
					<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$_POST['restID']; ?>">
				<?php }else{ ?>
					<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/dishProfile.php?gdId='.$row['gid']; ?>">
				<?php
				}
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
			<div id="userComment" style="width: 170px;">
					<table style="width: 99%;">
						<tr>
							<td width='50%'>
							<?php if($row['gid'] == null) {?>
								<a class='dish' href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$_POST['restID']; ?>">
							<?php }else{ ?>
								<a class='dish' href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/dishProfile.php?gdId='.$row['gid']; ?>">							
								<?php }echo substr($row['name'], 0, 22),'...'; ?>
								</a>
							</td>
							<td><img src='<?php echo $rm->ratingInStar($row['rating']/$row['noOfRatings']); ?>'/></td>
						</tr>
						<tr>
							<td colspan="2">
							<?php
	
								$comment = "Description not available";
								if(isset($row['desc']))
								{
									$comment = $row['dscr']; 
								}
								elseif(isset($row['comment']))
									$comment = $row['comment']; 
								elseif($row['gid'])
									$comment = $row['description']; 
									
								$wrapped = wordwrap($comment, 50, "--split--"); 
								//echo $wrapped;
								$str_array = preg_split('[--split--]', $wrapped);
								echo $str_array[0], "..."; 
							
							?></td>
						</tr> 
					</table>
			</div>
		</div>
	<?php
		$i++; 
	}
	?>
	<script type="text/javascript">
	$('#paging').each(function(){
		$(this).find('a').hover(function(event) {
			$(this).toggleClass('ui-state-hover');
		});
	});
	
	$('#dishNext').click(function(){
		<?php if($i < $n){
			echo "return false;"; 
		}else{
		?>
		//show dishes
		$("#tDishes").load("rest_dishes.php", {restID: <?php echo $_POST['restID']; ?>, page: <?php echo $page+1; ?>});
		<?php } ?>
	});
	
	
	
	$('#dishPrev').click(function(){
		<?php if($page == 0){
			echo "return false;"; 
		}else{
		?>
		$("#tDishes").load("rest_dishes.php", {restID: <?php echo $_POST['restID']; ?>, page: <?php echo $page-1; ?>});
		<?php } ?>
	});
	
	
	</script>
	<?php
}
?>