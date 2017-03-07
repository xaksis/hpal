<?php 
if(isset($_POST['page']))
{
	session_start(); 
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/feedbackModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$offset = $_POST['n']; 
	$start = $_POST['page']*$offset; 

	$fm = new FeedbackModel(); 
	$response = new ResponseObject();
	$feedbacks = array(); 
	$response = $fm->getFeedbacks($start, $offset, $feedbacks);
	if($response->code==0)
	{
		?>
		<div class="ui-widget">
				<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
					No feedback yet. Please Feel free to send one!</p>
				</div>
		</div>
		<?php
		return;
	}
	 $i=0; 
	foreach($feedbacks as $row) {
	$i++; 
	?>
		<div class="singlefb" style="color: black; margin-left: 15px; min-height: 40px; border-bottom: 2px solid #d1d1d1;">
			<div id="name" style="min-height: 20px; float: left; margin: 10px 10px 10px 0px;">
				<div id="fb_nameWrapper" class="dish"><?php if(isset($row['username']))echo $row['username']; else echo "Guest"; ?></div>
				<div id="dateWrapper"><?php echo $response->formatDate($row['created']); ?></div>
			</div>
			<div id="userfeedback" style=" max-width: 440px;float: left; margin: 10px 0px 10px 0px; min-height: 20px;">
				<h3 style=""><?php echo $row['subject']; ?></h3>
				<?php echo $row['comment']; ?>
			</div>
			<div id="clear"></div>
			<?php 
			if($row['reply'] != '')
			{
			?>
			<div id="reply">
				
				<div id="replier" class="rest" style="float: left; margin-right: 10px;">
					<div style="float: left; border: none;" class="ui-state-default" title=".ui-icon-arrowreturnthick-1-e">
						<span class="ui-icon ui-icon-arrowreturnthick-1-e"></span>
					</div>
					Haplette
				</div>
				<div style="float: left; max-width: 440px; margin-bottom: 10px">
					<?php echo $row['reply']; ?>
				</div>
				<div id="clear"></div>
			</div>
			<?php
			}
			?>
		</div>
	<?php
	}
	if(count($feedbacks) > $offset)
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