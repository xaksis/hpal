<?php
	if(!isset($_POST))
		return;
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
$rm = new RestModel();
session_start();
?>

<script type="text/javascript">
$(document).ready(function(){
	function updateRating(slide)
	{ 
		$("#rateValue").html($(slide).slider('value'));
	}
	<?php
	if(isset($_SESSION['id']))
	{
	?>
	$("#rateSlider").slider({
		orientation: "horizontal",
		range: "min",
		min: 0,
		max: 5,
		step: 1,
		value: <?php echo $_POST['rating'];?>,
		slide: function(event, ui){
					var slide = this;
					setTimeout(function(){updateRating(slide)}, 10); 
					//$(this).siblings("#rating").html($(this).slider('value')); 
				},
		stop: function(event, ui) { 
					if($('#rateValue').html() == 0)
						return;
					$('#cont').html('Thank you!');
					//call the function on main page to perform action
					saveRating('<?php echo $_POST['from']; ?>', $("#rateValue").html()); 
				}
	});	
	<?php
	}
	?>
	$('#container').mouseover(function(){
		$('#rateImg').hide();
		$('#rateSlider').show();
	});
	$('#container').mouseout(function(){
		$('#rateImg').show();
		$('#rateSlider').hide();
	});
	
});	
</script>

<div id="container">
	<div id="cont" style="float: left;">
		<?php 
	if(isset($_SESSION['id']))
	{
		?>
		<div id='rateSlider' style="display: none; width: 70px;"></div>
	<?php }
	else
	{
		?>
		<div id='rateSlider' style="display: none; width: 70px;">Please Login</div>
		<?php
	}
	?>
		<img id="rateImg" src='<?php echo $rm->ratingInStar($_POST['rating']); ?>'/>
	</div>
	<div id="rateValue" style="float: right;"><?php echo $_POST['rating'];?></div>
</div>