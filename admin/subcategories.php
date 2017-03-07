<?php
if(isset($_POST))
{
	$selectId = $_POST['selectId']; 
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	$rm = new RestModel(); 
	$response = new ResponseObject(); 
	//get subcategories
	$subcategories = array();
	$response = $rm->getSubCategories($subcategories); 
?>

<tr>
	<td>
		Categories:
		<select class="subcat_sel" id="<?php echo $selectId;?>">
			<option value='None'>Select One</option>
			<?php
				if($selectId == 3)
				{
					echo "<option value='Place Type'>Place Type</option>"; 
				}
				else
				{
					foreach ($subcategories as $row) {
							echo "<option value='".$row['subcat']."'>".$row['subcat']."</option>"; 
					}
				}
			?>
		</select>
		<span id="cuisine<?php echo $selectId;?>" >
		</span>
	</td>
</tr>
<tr>
	<td>
	<a href="#" class="addCat" style="text-decoration: underline;" id="link<?php echo $selectId;?>">Add A Cuisine</a>
	</td>
</tr>
<script type="text/javascript">
	$('select.subcat_sel#<?php echo $selectId?>').change(function(){
												 
												var subCat = $('select.subcat_sel#<?php echo $selectId?>').val();
												$('#cuisine<?php echo $selectId?>').load("http://<?php echo $_SERVER['HTTP_HOST']?>/hp/admin/cuisines.php", 
													{subcat: subCat, selectId: <?php echo $selectId; ?>}
												);
											});
	$("a.addCat#link<?php echo $selectId;?>").click(function(){
		var $link = $(this); 
		$.ajax({
					url: "http://<?php echo $_SERVER['HTTP_HOST']?>/hp/admin/subcategories.php",
					global: false,
					type: "POST",
					data: ({selectId: <?php echo $selectId+1; ?>}),
					dataType: "html",
					success: function(html){
						$link.parent().parent().parent().append(html); 
						$link.parent().parent().remove();
					}
				}
			);
		return false; 
	});
</script>
<?php
}
?>