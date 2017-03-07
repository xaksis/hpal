<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	include 'necessities.php';
	$page = 'home';
?>

<title>Happy Palette - Home</title>
<script type="text/javascript">


$(document).ready(function(){

	//constants
	var page = 0; 
	var dPage = 0; 
	var active = "place";

	/*STAGE EFFECTS*/
	$("#restUpdate span").css("opacity", 0);
	$("#dishUpdate span").css("opacity", 0);
	//on mouse over
	$("#restUpdate span").hover(function () {
			// animate opacity to full
			$(this).stop().animate({
					opacity: 1
					}, "slow");
		},
		// on mouse out
		function () {
			// animate opacity to nill
			$(this).stop().animate({
				opacity: 0
			}, "slow");
		});
	//on mouse over
	$("#dishUpdate span").hover(function () {
			// animate opacity to full
			$(this).stop().animate({
				opacity: 1
			}, "slow");
		},
		// on mouse out
		function () {
			// animate opacity to nill
			$(this).stop().animate({
				opacity: 0
			}, "slow");
		});
	
		
		$("#restUpdate span").click(function(){
		$("a#restUpdate").css("background-image", "url('./images/update_rest_on.png')");
		$("a#dishUpdate").css("background-image", "url('./images/update_dish_off.png')");
		//$("#stageTitle #title").css("background-image", "url('./images/update_rest_title.png')");
		//$("#stageTitle #title").css("background-repeat", "no-repeat");
		});
		
	 $("#dishUpdate span").click(function(){
		$("a#dishUpdate").css("background-image", "url('./images/update_dish_on.png')");
		$("a#restUpdate").css("background-image", "url('./images/update_rest_off.png')");
		//$("#stageTitle #title").css("background-image", "url('./images/update_dish_title.png')");
		//$("#stageTitle #title").css("background-repeat", "no-repeat");
		});
		
		
		$("#restUpdate span").click(function(){
			//$('#updates').html("");
			//$("#updates").load("update_stage.php", {page: page}, function(){ active="place"});
		});
		
		$("#dishUpdate span").click(function(){
			//$('#updates').html("");
			//$("#updates").load("update_stage_dish.php", {page: dPage}, function(){ active="dish"});
		});
		
		
		
		
		$("#featuredMap").load('featuredMap.php', {type: 'rest'});
		
		$('#dishEntry').autocomplete({source: 'qsGetDishNames.php', 
								  select: function(event, ui){
										$('#rrHolder').fadeOut("slow", function() {
											$("#rrHolder").load('qsBestRestForDish.php', {q_str: ui.item.value}, function(){
												$('#rrHolder').fadeIn("slow"); 
											});
										}); 	
									}
								});
								
	$('#restEntry').autocomplete({source: 'qsGetRestaurantNames.php', 
									select: function(event, ui){
										$('#drHolder').fadeOut("slow", function() {
											$("#drHolder").load('qsBestDishesForRest.php', {q_str: ui.item.value}, function(){
												$('#drHolder').fadeIn("slow"); 
											});
										}); 
									}
								});
	
	//initial value
	$("#restUpdate span").click();
	
	$('#dishEntry').val('chicken lollipop'); 
	$("#rrHolder").load('qsBestRestForDish.php', {q_str: $('#dishEntry').val()});
	
	
	$('#restEntry').val('Cafe Orlin'); 
	$("#drHolder").load('qsBestDishesForRest.php', {q_str: $('#restEntry').val()});
 });      

</script>

</head>

<body>
	<div id="outerWrapper">
		<div id="header">
        	<?php include 'header.php' ?>
        </div>
        
        <div id="content">
        	<div id="stage" style="margin-bottom: 10px; ">
                <div id="fork">
                	<a id="restUpdate" href="#" onclick="return false;">
                    	<span>
                        </span>
                    </a>
                </div>
                <div id="knife">
                	<a id="dishUpdate" href="#" onclick="return false;">
                    	<span>
                        </span>
                    </a>
                </div>
                <div id="featuredMap">
				</div>
                                
            </div><!--STAGE ENDS HERE-->
            
			<div style="float: left;">
				<!--
				<div id="commentsTitle" style="margin-top: 20px; width: 650px;">
					<ul id="info_nav">
						<li class="active">All Time</li>
						<li>This Week</li>
						<li>This Month</li>
					</ul>
				</div>
				<div id="top" style="width: 685px; height: 540px;">
				</div>
				-->
				<div id="qsWrap">
				
					<div class="qsQuery" id="qsQueryDish">
						<input type="text" id="dishEntry" class="text ui-widget-content ui-corner-all" />
					</div>
					<div class="qsQuery" id="qsQueryRest">
						<input type="text" id="restEntry" class="text ui-widget-content ui-corner-all" />
					</div>
					<div id="clear">
					</div>
					
					<div id="restResult" style="float: left; width: 337px; min-height: 400px;">
						<div id='rrHolder'>
							<img id="restLoading" style="margin: 120px 0px 0px 120px; display: none;" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/hp/images/loading.gif" />
						</div>
					</div>
					
					<div id="dishResult" style="float: left;">
						<div id='drHolder'>
							<img id="dishLoading" style="margin: 120px 0px 0px 120px; display: none;" src="http://<?php echo $_SERVER['HTTP_HOST']; ?>/hp/images/loading.gif" />
						</div>
					</div>   
					<div id="clear">
					</div>
				
				</div><!----qsWrap---->
				
			</div>
            
            <div id="pick_list_wrapper">
			
				<!--<fb:add-to-timeline show-faces="true" mode="button"></fb:add-to-timeline>-->
				<button id="add-rest">Add a Restaurant</button>
            	<!--<div id="pick_title">
					<span>New At Haplette</span>
                </div>
                <div id="pick_list">
                    <ol>
                    	<li><a>Federated Login</a></li>
                        <li>Saint Alp's at <a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/favorite.php'; ?>">Favorites</a></li>
                    </ol>
                </div>
                -->
            </div>
            
            
        </div>
        
        <div id="footer">
			<?php include 'footer.php'; ?>
        </div>
	</div>

</body>
</html>
