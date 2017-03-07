<script type="text/javascript">
$(document).ready(function(){
		
	$('#restLink').click(function(){
		//check if restLink is already active
		$(this).css("color", "#626262");
		$("#dishLink").css("color", "#aaaaaa");
		if($("div#topRest").is(":hidden")){
			$("#topDish").hide("slide",{direction: "up"}, "slow", function(){
				$("#topRest").show("slide", { direction: "up" }, "slow");
			});
		}
		return false;
	});
	
	$('#dishLink').click(function(){
		//check if restLink is already active
		$(this).css("color", "#626262");
		$("#restLink").css("color", "#aaaaaa");
		
		if($("div#topDish").is(":hidden")){
			$("#topRest").hide("slide",{direction: "up"}, "slow", function(){
				$("#topDish").show("slide", { direction: "up" }, "slow");
			});
		}
		
		return false;
	});
        
 }); 
</script>

<a href='#' id="restLink" style="color: #626262;">
<h3  style='float: left; margin-bottom: 10px; display: block; width: 100px; border-right: 2px solid #a1a1a1;'>Restaurants</h3>
</a>
<a href='#' id="dishLink" style="color: #aaaaaa;">
<h3 id="dishLink" style='float: left; margin-bottom: 10px; margin-left: 10px; display: block; width: 100px;'>Dishes</h3>
</a>
<div id="clear"></div>
<div id="topRest">
<?php 
	for($i=0; $i<4; $i++)
	{
	?>
		<div style="padding-top:1px; height: 120px; <?php if($i%2) echo 'background-color: #edecec;';?>">
			<table style='margin-top: 20px; width: 100%; text-align: center;'>
				<tr>
					<?php for($rowi=0; $rowi<3; $rowi++){?>
						<td>
							<table style='text-align: left; font-weight: bold;'>
							<tr>
								<td><?php echo $i*3+$rowi+1; ?></td>
								<td rowspan="4"><a href="./restProfile.php"> <img style='border: none;' src="./images/thumb.png" /></a></td>
								<td><?php if($rowi%2) echo 'East Village Thai'; else echo 'Klong';?></td>
							</tr>
							<tr>
								<td></td>
								<td><img src="./images/star/star4.png" /></td>
							</tr>
							<tr>
								<td></td>
								<td><img src="./images/star/oStar3_5.png" /></td>
							</tr>
							<tr>
								<td></td>
								<td>Genre: Thai</td>
							</tr>
							</table>
						</td>
					<?php } ?>
					<td>
					</td>
					<td>
					</td>
				</tr>
			</table>
		</div>
	<?php	
	}
?>
</div>

<div id="topDish" style='display: none;'>
<?php 
	for($i=0; $i<4; $i++)
	{
	?>
		<div style="padding-top:1px; height: 120px; <?php if($i%2) echo 'background-color: #edecec;';?>">
			<table style='margin-top: 20px; width: 100%; text-align: center;'>
				<tr>
					<?php for($rowi=0; $rowi<3; $rowi++){?>
						<td>
							<table style='text-align: left; font-weight: bold;'>
							<tr>
								<td><?php echo $i*3+$rowi+1; ?></td>
								<td rowspan="4"><a href="./restProfile.php"> <img style='border: none;' src="./images/thumb.png" /></a></td>
								<td><?php if($rowi%2) echo 'Pad Thai'; else echo 'Pad Krow Pow';?></td>
							</tr>
							<tr>
								<td></td>
								<td><img src="./images/star/star4.png" /></td>
							</tr>
							<tr>
								<td></td>
								<td><img src="./images/star/oStar3_5.png" /></td>
							</tr>
							<tr>
								<td></td>
								<td>Genre: Thai</td>
							</tr>
							</table>
						</td>
					<?php } ?>
					<td>
					</td>
					<td>
					</td>
				</tr>
			</table>
		</div>
	<?php	
	}
?>
</div>
