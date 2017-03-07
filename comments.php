<?php 
if(isset($_POST['restID']))
{
	session_start(); 
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$offset = $_POST['n']; 
	$start = $_POST['page']*$offset; 

	$rm = new RestModel(); 
	$response = new ResponseObject();
	$reviews = array(); 
	$response = $rm->getRestaurantReviews($_POST['restID'], $start, $offset, $reviews);
	if(count($reviews)==0)
	{
		?>
		<div class="ui-widget">
				<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					No user reviews for this restaurants yet. Be the first to write one!</p>
				</div>
		</div>
		<?php
		return;
	}
	 $i=0; 
	foreach($reviews as $row) {
	$i++; 
	?>
		<div class="comment" style="color: black;" id="<?php if($i%2){echo "commentEven";}else{echo "commentOdd";} ?>">
			<div id="name">
				<div id="nameWrapper"><?php echo $row['username']; ?></div>
				<div id="dateWrapper"><?php echo $response->formatDate($row['created']); ?></div>
			</div>
			<div id="userComment">
				<h3 style="float: left;"><?php echo $row['title'] ?></h3>
				<img style='margin-left:10px; float: right;' src='<?php echo $rm->ratingInStar($row['rating']); ?>'/>
				<div id="clear"></div>
				<?php echo $row['comment'] ?>
			</div>
		</div>
	<?php
	}
	if(count($reviews) > $offset)
	{
	?>
	
	<div id="listNavigation">
		<h3 style="float: left;">Comment <?php echo "Page ", $_POST['page']+1; ?></h3>
		<div style="float:right;">
			<a id="list_left" class="nav" style="margin-right: 10px;" href="javascript: listLeft();"><img border="0" src="./images/<?php if($start == 0)echo "list_left_off.png"; else echo "list_left_on.png"?>" /></a>
			<a id="list_right" class="nav" style="margin-right: 10px;" href="javascript: listRight();"><img border="0" src="./images/<?php echo "list_right_on.png"?>"/></a>
		</div>
	</div>
<?php
	}
}
?>