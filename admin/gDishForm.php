<?php
if(isset($_POST['dishId']))
{
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	
	$dm = new DishModel();
	$response = new ResponseObject(); 
	$dResponse = new ResponseObject(); 
	
	$dResponse = $dm->getDishById($_POST['dishId'], $dRow); 
	
	$selected = ""; 
	
	$dishes= array(); 
	$response = $dm->getGenericDishes($dishes);
	echo 'Pick Dish '; 
	
	echo "<select id='gd_sel'>"; 
		foreach ($dishes as $row) {
			if($dRow['gDish_id'] == $row['id'])
				$selected = " selected='true' "; 
			else
				$selected = ""; 
			echo "<option value='".$row['id']."'".$selected.">".$row['name']."</option>"; 
		}
	echo "</select>";
	?>
	<form>
	or <a id="add" href="#">Add:</a> <input style="display: none;" id="gd_entry" type="text"/><br>
	Description: <br/>
	<textarea style="overflow: auto; resize: none; " cols="28" rows="4" id="gd_description" />
	<br/>Family Name: <input id="gd_family" type="text"/> <br/>
	[example: <br/>
	green curry fried rice > fried Rice, <br/>
	sheesh kabab > Kabab, Noodles...] <br/>
	<input id="gd_btn" type="button" value="Submit"/>
	
<script type="text/javascript">
	
	
	
	function populateEntries(data)
	{
		$("#gd_description").val(data.description); 
		$("#gd_family").val(data.familyName); 
	}
	
	function populateForm()
	{
		var gdishId = $('#gd_sel option:selected').val(); 
			
		$.ajax({
				url: "getGenericDishInfo.php",
				global: false,
				type: "POST",
				data: ({gdish_id: gdishId}),
				dataType: "json",
				success: function(data){
					populateEntries(data); 
				}
			}
		);
	}
	
	$('#gd_sel').change(function(){
		populateForm(); 
	});
	
	
	$('#add').click(function(){
		if( $('#gd_entry').is(':hidden') ) {
			$("#gd_entry").show();
			$("#gd_sel").attr('disabled', true);
			$("#gd_description").val(""); 
			$("#gd_family").val("");
		}
		else
		{
			$("#gd_entry").hide(); 
			$("#gd_sel").attr('disabled', false);
			//re populate the other fields. 
			populateForm();
		}
	});
	
	$('#gd_btn').click(function(){
		//check if we're updating or adding new
		if( $('#gd_entry').is(':hidden') ) {
			//updating
			var gdishId = $('#gd_sel option:selected').val();
			var desc = $('#gd_description').val(); 
			var fam = $('#gd_family').val(); 
			$.ajax({
				url: "updateGenericDish.php",
				global: false,
				type: "POST",
				data: ({gd_id: gdishId, gd_desc: desc, family: fam}),
				dataType: "html",
				success: function(data){
					$('#gDishContainer').load('addGenericToDish.php', {d_id: <?php echo $_POST['dishId']; ?>, gd_id: gdishId}); 
				}
			});
		}
		else{
			//adding new
			var gdishName = $('#gd_entry').val();
			var desc = $('#gd_description').val(); 
			var fam = $('#gd_family').val(); 
			$.ajax({
				url: "updateGenericDish.php",
				global: false,
				type: "POST",
				data: ({gd_name: gdishName, gd_desc: desc, family: fam}),
				dataType: "html",
				success: function(data){
					$('#gDishContainer').load('addGenericToDish.php', {d_id: <?php echo $_POST['dishId']; ?>, gd_id: data}); 
				}
			});
		}
	});
	
	$('#gd_sel').change(); 
	
</script>
	<?php
}
?>