<?php session_start(); ?>
<script type="text/javascript">

$(document).ready(function(){


	$('#savePic_btn').button().click(function(){
		$(".imageForm").each(function(){
			//if delete is checked
			var thisDiv = this;
			var image_id = $(this).attr("id");
			var caption; 
			if($(this).find("#is_dish").is(':checked'))
				caption = $(this).find("#dishEntry").val();
			else
				caption = $(this).find("#cap_entry").val();
			
			var image_id = $(this).attr("id");
			if($(this).find("#deleteThis").is(':checked'))
			{
				$.ajax({
						url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/admin/deleteImage.php'; ?>",
						global: false,
						type: "POST",
						data: ({img_id: image_id}),
						dataType: "html",
						success: function(html){
							$(thisDiv).parent().html(html); 
						}
					});
			}
			else if(!($(this).find("#is_dish").is(':checked')) && caption == "")
			{
				//do nothing
			}
			else
			{
				var dishId=0; 
				
				//if dish info is provided
				if($(this).find("#is_dish").is(':checked'))
				{
					var restId = $(this).find('.rest_info').attr("id");  
					var rating = $(this).find("#rating").html();
					var comment = $(this).find("#comment").val(); 
					var restName = $(this).find("#restEntry").val();
					caption = $(this).find("#dishEntry").val();
					$.ajax({
						url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/admin/saveOrUpdateDish.php'; ?>",
						global: false,
						type: "POST",
						data: ({rest_id: restId,
								rest_name: restName,
								name: caption, 
								rating: rating, 
								comment: comment, 
								user_id: <?php echo $_SESSION['id']; ?>
								}),
						dataType: "html",
						success: function(html){
							dishId=html;
							//save image caption
							$.ajax({
								url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/admin/updateImage.php'; ?>",
								global: false,
								type: "POST",
								data: ({img_id: image_id,
										dish_id: dishId,
										rest_name: restName,
										caption: caption
										}),
								dataType: "html",
								success: function(html){
									$(thisDiv).parent().html(html); 
								}
							});
						}
					});
				}
				else
				{
					var restName = $(this).find("#restEntry").val();
					//save image caption
					$.ajax({
						url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/admin/updateImage.php'; ?>",
						global: false,
						type: "POST",
						data: ({img_id: image_id,
								dish_id: dishId,
								rest_name: restName,
								caption: caption
								}),
						dataType: "html",
						success: function(html){
							$(thisDiv).parent().html(html); 
						}
					});
				}
			}
		});
	});

	$('#cancel_btn').each(function(){
		$(this).button().click(function(){
			$('#picWrapper').dialog("close"); 
		});
	});
	
	
	//make all widgets invisible
	$(".imageForm #dish_info").hide();
	
	/*SLIDERS*/
	function updateRating(slide)
	{ 
		$(slide).siblings("#rating").html($(slide).slider('value'));
	}
	
	$(".imageForm #rate_bar").slider({
		orientation: "horizontal",
		range: "min",
		min: 0,
		max: 5,
		step: 1,
		slide: function(event, ui){
					var slide = this;
					setTimeout(function(){updateRating(slide)}, 10); 
					//$(this).siblings("#rating").html($(this).slider('value')); 
				}
	});

	$(".imageForm #is_dish").each(function(){
		//get parent div's id
		$( this ).bind (
			"click",
			function(){
				var divId = $(this).parent().attr("id");
				if($(this).is(':checked'))
				{
					$(this).siblings("#cap_lbl").html("Dish Name:");
					$(this).siblings("#cap_entry").hide();
					$(this).siblings("#dishEntry").show();
					$(this).siblings("#dish_info").show(); 
				}
				else
				{
					$(this).siblings("#cap_lbl").html("Caption:");
					$(this).siblings("#cap_entry").show();
					$(this).siblings("#dishEntry").hide();
					$(this).siblings("#dish_info").hide();
				}
			}
		);
	});
	
	$(".imageForm").each(function(){
		$(this).find("#restEntry").autocomplete({
									source: 'qsGetRestaurantNames.php', 
									select: function(event, ui){
										$(this).siblings("#dishEntry").autocomplete({
											source: function(request, response) {
												$.ajax({
													url: 'getDishesGivenRestName.php',
													dataType: "json",
													data: {
														term : request.term,
														rest : ui.item.value
													},
													success: function(data) {
														response(data);
													}
												});
											}
										}); 
									}
								});
	});
	
}); 
</script>
<form>
<input id="savePic_btn" type="button" value="Save"/> <input id="cancel_btn" type="button" value="Cancel"/>			
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dish.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';

require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

$pictures = array(); 
$imageModel = new ImageModel(); 
$response = new ResponseObject(); 
/*
$dish = new Dish(); 
$dm = new DishModel();

$dish->name="Pad Thai"; 
$dish->restaurant_id=2;
$dish->user_id=6; 
$dish->rating=3;  
 
$response = $dm->createOrUpdateDish($dish); 

echo $response->explanation; 
*/

$response = $imageModel->getUnconfirmed($_SESSION['id'], $pictures); 

foreach ($pictures as $row) {
?>
	
	<div class="imageHolder" id="<?php echo $row['id']?>_outer">
		<div id="imageCradle">
			<img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$row['imagePath']; ?>" />
		</div>
		<div class="imageForm" id="<?php echo $row['id']?>">
				<span class="rest_info" id="<?php echo $row['restaurant_id'] ?>">Restaurant Name:</span>
				<input type="text" id="restEntry" style="margin:0px;" class="text ui-widget-content ui-corner-all" /></br>
				
				Delete this image: <input style="margin-right:0px;"type="checkbox" id="deleteThis"/><br/>
				This is a Dish: <input style="margin-right: 0px;"type="checkbox" id="is_dish"/> 
				
				 
				<br/><span id="cap_lbl">Caption: </span><input id="cap_entry" type="text" class="text"/>
				<input type="text" id="dishEntry" style="display: none; margin: 0px;"class="text ui-widget-content ui-corner-all" /><br/>
				<div id="dish_info">
					<div style="margin-top: 10px; float: left;">Rating bar: </div> 
					<div style="margin: 10px 0px 0px 20px; float: right; width: 150px;" id="rate_bar"></div>
					<span style="display: block; float: right; margin: 10px 0px 0px 10px" id="rating">0</span>
					<div id="clear"></div>
					Comment:
					<textarea style="overflow: auto; resize: none; " cols="42" rows="2" id="comment" />
				</div>
		</div>
		<div id="clear">
		</div>
	</div>

<?php
}
?>
<input id="savePic_btn" type="button" value="Save"/>
			<input id="cancel_btn" type="button" value="Cancel"/>
</form>