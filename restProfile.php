<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	$restId=1; 
	if(isset($_GET) && isset($_GET['restId']))
		$restId=$_GET['restId']; 
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
	
	$rm = new RestModel(); 
	$restaurant = new Restaurant(); 
	
	$response = new ResponseObject(); 
	
	$response = $rm->getRestaurantById($restId, $restaurant); 
	
	$im = new ImageModel(); 
	
	$images = array();
	$imgResp = new ResponseObject();
	$imgResp = $rm->getNonDishImageForRestaurant($restId, $images[0]);
	$imageURL = "http://".$_SERVER['HTTP_HOST'].":8081/hp/images/noRestaurantPic.png"; 
	if($imgResp->code!=0)
	{
		$imageURL = "http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($images[0]['imagePath']); 
	}
	
  $des =  "".$restaurant->name;
  $des .= " in ".$restaurant->city;
  $des .= ", Browse through images, menu and top dishes from this place!"; 
	$restURL = "http://".$_SERVER['HTTP_HOST'].':8081/hp/restProfile?restId='.$restId; 
	$realURL = "http://".$_SERVER['HTTP_HOST'].'/hp/restProfile?restId='.$restId; 
?>

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"
    xmlns:fb="https://www.facebook.com/2008/fbml"
	xmlns:og="http://ogp.me/ns#">
<head prefix="og: http://ogp.me/ns# 
			aa_haplette: http://ogp.me/ns/apps/aa_haplette#"> 
<meta property="fb:app_id" content="164932876924803" /> 
<meta property="og:title" content="<?php echo $restaurant->name; ?>" />
<meta property="og:type" content="aa_haplette:restaurant" />
<meta property="og:url" content="<?php echo $realURL; ?>" />
<meta property="og:image" content="<?php echo $imageURL; ?>" />
<meta property="og:description" content="<?php echo $des; ?>" /> 
<meta property="og:site_name" content="haplette" />
<base href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/'; ?>" />
<meta name="description" content="<?php echo $des; ?>">
<meta name="keywords" content="<?php echo $restaurant->name; ?>, Reviews, menu, <?php echo $restaurant->address; ?>">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<?php include 'necessities.php'?>


<title>Grub Pal - <?php echo $restaurant->name?> NYC Restaurant</title>

</head>

<script type="text/javascript">

	function postAdd()
    { 
        FB.api('/me/aa_haplette:add' + 
                    '?restaurant=<?php echo urlencode($realURL); ?>','post',
            function(response) {
				if (!response || response.error) {
					console.log(response);
                    alert('Error occured:'+response.error);
				} else {
					alert('The place was added to your timeline');
                }
        });
    }


	var geocoder;
	var map;
  	function initialize() {
    	geocoder = new google.maps.Geocoder();
    	var latlng = new google.maps.LatLng(<?php echo $restaurant->lat; ?>, <?php echo $restaurant->lon; ?>);
    	var myOptions = {
      		zoom: 14,
      		center: latlng,
      		mapTypeId: google.maps.MapTypeId.ROADMAP
    	}
		
    	map = new google.maps.Map(document.getElementById("map"), myOptions);
		var marker = new google.maps.Marker({
              			position: latlng,
              			map: map
          			});
	}

  	function codeAddress() {
    	var address = "39 3rd Ave, New York 10003";
    	geocoder.geocode( { address: address}, function(results, status) {
      		if (status == google.maps.GeocoderStatus.OK && results.length) {
        	// You should always check that a result was returned, as it is
        	// possible to return an empty results object.
        		if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {
          			map.setCenter(results[0].geometry.location);
          			var marker = new google.maps.Marker({
              			position: results[0].geometry.location,
              			map: map
          			});
        		}
      		} else {
        		alert("Geocode was unsuccessful due to: " + status);
      		}
    	});
  	}
	//global for comment list
	var commentStart = 0;
	var totalComment = 42;  
	function listRight(){
		if(commentStart+5 <= totalComment){
			commentStart += 5; 
			$('#comments').html("<img id='loading' style='margin: 100px 0px 0px 150px;' src='./images/loading.gif'/>");
			$("#comments").load("comments.php", {restID: 1, start: commentStart});
		}
	}
	
	function listLeft(){
		if(commentStart-5 >= 0){
			commentStart -= 5; 
			$('#comments').html("<img id='loading' style='margin: 100px 0px 0px 150px;' src='./images/loading.gif'/>");
			$("#comments").load("comments.php", {restID: 1, start: commentStart});
		}
	}

	function saveRating(target, rating)
	{
		if(target=='rest')
		{
			$.ajax({
				url: "saveReview.php",
				global: false,
				type: "POST",
				data: ({restId: <?php echo $restId; ?>, 
						title: "NONE",
						comment: "Just Rating",
						rating: rating
					  }),
				dataType: "html",
				success: function(html){
					window.setTimeout('location.reload()', 800);
				}
			});
		}
	}
	
$(document).ready(function(){
	/*STAGE EFFECTS*/
	var page=0; 
	var totalPics=0; 
	var picsPerPage=3;
	
	$("#left_button_link span").css("opacity", 0);
	$("#right_button_link span").css("opacity", 0);
	//on mouse over
	$("#left_button_link span").hover(function () {
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
	$("#right_button_link span").hover(function () {
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
	/*STAGE EFFECTS END HERE*/
	
	$.ajax({
		url: "totalPics.php",
		global: false,
		type: "POST",
		data: ({rest_id: <?php echo $restId; ?>}),
		dataType: "html",
		success: function(html){
			totalPics=html;
		}
	});
	
	function getLastPage()
	{
		if(totalPics%picsPerPage == 0)
			return totalPics-picsPerPage; 
		else
			return Math.floor(totalPics/picsPerPage)*picsPerPage; 
	}
	
	$("#right_button_link span").click(function(){
		if(page+picsPerPage>=totalPics)
			page=0;
		else
			page=page+picsPerPage;  
		
		//call the next update
		$('#profile_stage').fadeOut("slow", function(){
			$('#profile_stage').load("rest_profile_stage.php", {page: page, rest_id: <?php echo $restId; ?>},  function(response, status, xhr) {
			  if (status == "error") {
				page=page-picsPerPage;
				if(page<0)
					page=picsPerPage-1; 
			  }else
			  {
				$('#profile_stage').fadeIn("slow"); 
			  }
			  
			  });
		});
		
	});
	
	$("#left_button_link span").click(function(){
	
		if(page<=0)
			page = getLastPage();
		else
			page=page-picsPerPage; 
		//call the next update
		$('#profile_stage').fadeOut("slow", function(){
			$('#profile_stage').load("rest_profile_stage.php", {page: page, rest_id: <?php echo $restId; ?>},  function(response, status, xhr) {
			  if (status == "error") {
				page++;
				if(page>picsPerPage-1)
					page=0; 
			  }else
			  {
				$('#profile_stage').fadeIn("slow"); 
			  }
			 
			 });
		}); 
		
	});
	
	
	//show pics in stage
	$("#profile_stage").load("./rest_profile_stage.php", {page: page, rest_id: <?php echo $restId; ?>});
	
	//show comments
	$("#tDishes").load("rest_dishes.php", {restID: <?php echo $restId; ?>, page: 0});
	$("#comments").load('comments.php', {restID: <?php echo $restId; ?>, page: 0, n: 5});
	
	$("#commentsTitle li").click(function(){
		if($(this).attr("class") == 'active')
			return false; 
		else
		{
			$("#commentsTitle li").each(function(){
				$(this).attr("class", "inactive");
			});
			$(this).attr("class", "active");
			var text = $(this).text(); 
			switch(text){
				case "Comments":
					$('#commentForm').show();
					$('#allDishes').hide(); 
					$("#comments").show(); 
					break;
				case "Menu":
					$('#commentForm').hide(); 
					$('#comments').hide(); 
					$("#allDishes").show();
					break;
				default:
					break;
			}
		}
	});

//check session and enable/disable comments	
	$.ajax({
			url: "checkSession.php",
			global: false,
			dataType: "html",
			success: function(html){
				if(html == "Logged In")
				{
					$('#loginToComment').hide();
					$('#formElems').show();
					$("#rate_bar").slider({
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
				}
				else
				{ 
					$('#formElems').hide();
					$('#loginToComment').show();
				}
			}
		});
	
	$('#comment_entry').keyup(function(e){
		var code = (e.keycode? e.keycode : e.which);
		if(code == 13)
		{
			$.ajax({
				url: "checkSession.php",
				global: false,
				dataType: "html",
				success: function(html){
					if(html == "Logged In")
					{
						$.ajax({
							url: "saveReview.php",
							global: false,
							type: "POST",
							data: ({restId: <?php echo $restId; ?>, 
									title: $('#title_entry').val(),
									comment: $('#comment_entry').val(),
									rating: $("#uRating").html()
								  }),
							dataType: "html",
							success: function(html){
								if(html == "Review Added!")
								{
									$('#commentElements').html(html); 
									$('#commentElements').hide('blind', 'slow', function(){
											$("#comments").load('comments.php', {restID: <?php echo $restId; ?>, page: 0, n: 5});
											$('#addComment').show();
										}
									);
									
								}
								else
								{
									$('#SaveResponse').html("<div class=\"ui-widget\"><div class=\"ui-state-error ui-corner-all\" style=\"padding: 0 .7em;\"> <p><span class=\"ui-icon ui-icon-alert\" style=\"float: left; margin-right: .3em;\"></span> <strong>Alert:</strong>"+html+"</p></div></div>");
								}
							}
						});
					}
					else
					{ 
						//$('#login-modal').dialog("open");
					}
				}
			});
			return false; 
		}
	});
	
	
	$('body').click(function() {
		if($('#commentElements').is(':visible') && !$('#login-modal').is(':visible'))
		{
			$('#commentElements').hide('blind', 'slow', function(){
													$('.comment').last().show();
													$('#addComment').show();
													}
								);
		}
	 });


	 
	 $('#addComment').click(function(){
		$('#addComment').hide();
		if($('#comments').size() > 4)
			$('.comment').last().hide(); 
		$('#commentElements').show('blind', 'slow', function(){$('#title_entry').focus();});
		return false;
	});
	
	 $('#commentElements').click(function(event){
		 return false; 
	 });
	 
	//initialize map
	initialize();
	//code address
	//codeAddress();
	function updateRating(slide)
	{ 
		$(slide).siblings("#uRating").html($(slide).slider('value'));
	}

	$("#restRateWidget").load("rater.php", {rating: $('#restRateWidget').html(), from: 'rest', rUrl: '<?php echo $realURL ?>'});
	$("#menuCats").accordion({
		fillSpace: true
	}); 
	
	$(".sidebar_link").each(function(){
		$(this).bind("click", function(){
			$(".sidebar_link").each(function(){
				$(this).removeClass('active');
			});
			$(this).addClass('active');
			var divName = $(this).attr('id')+'_dishes';
			var foundPrev = false; 
			$('.menuPage').each(function(){
				if($(this).is(":visible"))
				{
					foundPrev = true; 
					$(this).hide("fast", function(){
						$('#'+divName).show("slide", "slow"); 
					});
					
				}
			}); 
			if(!foundPrev)
				$('#'+divName).show("slide", "slow");
		});
	});
	
	$('#rate_bar').html('found it');
	
	$("#rate_bar").slider({
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
	
	$('#add_btn').button(); 
	$('#add_btn').click(function(){
		postAdd(); 
		return false;
	});
	
 });      

</script>

<body>
<?php 
	if($restaurant->imgCount == 1)
	{	//no images hide the stage
?>
	<div id="searchBody">
<?php
}
?>
	<div id="outerWrapper">
		<div id="header">
        	<?php include 'header.php' ?>
        </div>
        
        <div id="content" itemscope itemtype="http://schema.org/FoodEstablishment">
			<meta itemprop="image" content="<?php echo $imageURL; ?>" >
			<meta itemprop="description" content="<?php echo $des; ?>" >
        	<div id="stage_blank" <?php if($restaurant->imgCount == 1){echo 'style="height: 210px;"';} ?>>
				<div id="profile_title" itemprop="name">
					<?php echo $restaurant->name; ?>
					<!--
					<div style="float: right;">
						<input type="button" value="like!" onclick="javascript: postLike();"/>
						<?php $response->likeButton('http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile?restId='.$restId); ?>
					</div>
					-->
				</div>
				
                <div id="nav_button" <?php if($restaurant->imgCount == 1){echo 'style="display: none;"';} ?>>
                	<a id="left_button_link" href="#" onclick="return false;">
                    	<span>
                        </span>
                    </a>
                </div>
                
				<div id="profileStageWrapper" <?php if($restaurant->imgCount == 1){echo 'style="height: 180px; margin-left: 55px;"';} ?>>
					<div id="mainInfo">
					
					<div id="restInfoWrapper">
						<div id="restInfo">
							<div id="info" >
								<span id="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
									<span style="display: inline" itemprop="streetAddress"><?php echo $restaurant->address; ?></span> <br />
									<span style="display: inline" itemprop="addressLocality"><?php echo $restaurant->city; ?></span>,
									<span style="display: inline" itemprop="addressRegion"><?php echo $restaurant->state; ?></span>
									-<span style="display: inline" itemprop="postalCode"><?php echo $restaurant->zipcode; ?></span>
									<br />
								</span>
								<span id='phone' itemprop="telephone"><?php echo $restaurant->phone; ?></span> 
								
								
							</div>
							<div id="rating">
							<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
								<meta itemprop="ratingValue" content="<?php echo round($restaurant->adminRating/$restaurant->noOfRatings, 1); ?>">
								<meta itemprop="reviewCount" content="<?php echo $restaurant->noOfRatings; ?>">
							</span>
								<div id="restRateWidget" style="width: 100px; height: 20px;">
									<?php echo round($restaurant->adminRating/$restaurant->noOfRatings, 1); ?>
								</div>
								<!--<img style='float: left;' src='<?php echo $rm->ratingInStar($restaurant->adminRating/$restaurant->noOfRatings); ?>'/>
								<div class="number"><?php echo round($restaurant->adminRating/$restaurant->noOfRatings, 1); ?></div>
								!-->
								<div id="clear"></div>
								<div id='cats'>
									<span id="genre">Categories:</span>
									<?php 	$categories = array(); 
										$rm->getCategoriesForRestaurant($restaurant->id, $categories);
										$c_count=0;
										foreach($categories as $category){
											if($c_count!=0)
												echo ", "; 
											echo "<a class='rest' style='font-weight:normal; font-zize: 11px;' href='http://".$_SERVER['HTTP_HOST'].'/hp/search.php?sstr='.$category['name']."'>";
											echo "<span itemprop='servesCuisine'>";
											echo $category['name'];
											echo "</span>";
											echo "</a>";
											$c_count++; 
										}								
									?>
									</span>
								</div>
								
							</div>
							<div>
								<!--
									<?php if(isset($_SESSION['fb'])){?> 
									<a style="margin-left: 30px;" id="add_btn" href="#">Add To Your Palette on FB</a>
									<?php }?>
								-->
							</div>
						</div>
						
					</div>
					
					
					
					
					
						<div id="mapWrapper">
							<div id="map" style="height: 148px; width: 480px;">
							</div>
						</div>
					</div>
					<div id="profile_stage" <?php if($restaurant->imgCount == 1){echo 'style="display: none;"';} ?>>
					</div>
				</div>
				
                <div id="nav_button" <?php if($restaurant->imgCount == 1){echo 'style="display: none;"';} ?>>
                	<a id="right_button_link" href="#" onclick="return false;">
                    	<span>
                        </span>
                    </a>
                </div>
                                
            </div><!--STAGE ENDS HERE-->
            <div id="topDishWrap" <?php if($restaurant->imgCount == 1){echo 'style="display: none;"';} ?>>
				<div id="commentsTitle" style="width: 290px; margin: 0px 0px 0px 10px;">
                	<ul id="info_nav">
                    	<li class="active">Top Dishes</li>
                    </ul>
                </div>
				<div id="tDishes" class="" >
				</div>
			</div>
			
            <div id="commentsWrapper" <?php if($restaurant->imgCount == 1){echo 'style="width: 100%;"';} ?>>
            	<div id="commentsTitle" <?php if($restaurant->imgCount == 1){echo 'style="width: 763px; margin-left: 55px;"';} ?>>
                	<ul id="info_nav" style="float: left;">
                    	<li class="active">Menu</li>
						<li>Comments</li>
                    </ul>
                </div>
                <div id="commentForm">
					<div id="commentElements" style="display: none; border-bottom: 2px solid #5883db;">
						<div class="ui-widget" id='loginToComment'>
							<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
								<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
								Please Log in to Add a review</p>
							</div>
						</div>
						<div id='formElems'>
							<div id='SaveResponse'></div>
							<span class="dish" style="width: 70px; display: block; float: left; margin-top: 5px;">Rating</span>
							<div id="rate_bar" style="float: left; margin: 5px 0px 5px 10px; float: left; width: 100px; height: 10px;" ></div>
							<span style="display: block; float: left; margin: 5px 0px 0px 10px" id="uRating">0</span>
							<div id="clear"></div>
							<span class="dish" style="width: 70px; display: block; float: left;">Title</span>
							<input id="title_entry" class="text ui-widget-content ui-corner-all" />
							<div id="clear"></div>
							<span class="dish" style="width: 70px; display: block; float: left;">Comment</span>
							<textarea id="comment_entry" class="text ui-widget-content ui-corner-all"></textarea>
							<div id="clear"></div>
						</div>
					</div>
					<a id="addComment" class="ui-widget-content ui-corner-bottom" href="javascript: void(0);">Add Comment</a>
                </div>
				
                <!--<div id="dishes">
                	<img id="loading" style="margin: 100px 0px 0px 150px;" src="./images/loading.gif"/>
                </div>-->
				<div id="allDishes">
					<div <?php if($restaurant->imgCount == 1){echo 'id="menuWrapFull"';}else{echo 'id="menuWrap"';}?>>
						<div id="menuHeadings">
							<?php
								$dm = new DishModel();
								$mcats = array(); 
								$response = $dm->getMenuCategoriesForRestaurant($restId, $mcats); 
								foreach($mcats as $cat)
								{
									echo "<a href='javascript:void();' id='".str_replace(' ', '', $cat['name'])."' class='sidebar_link'><span class='label'>",$cat['name'],"</span></a>";
								}
								echo "<a href='javascript:void();' id='more' class='sidebar_link active'><span class='label'>More</span></a>";
							?>
						</div>
						<div id="menuPages" itemprop="menu">
							<!--<table id="menuHeader">
								<tr>
									<td width='20%'>Dishes</td>
									<td width='70%'>Description</td>
									<td width='10%'>Price</td>
								</tr>
							</table>-->
							<?php
								foreach($mcats as $cat)
								{
								
									echo "<div id='".str_replace(' ', '', $cat['name'])."_dishes' class='menuPage' style='display:none;'><table id='menuTable'>";
										//get all dishes for given menu category
										$dishes = array(); 
										$dresp = $dm->getRestuarantDishesForGivenFeature($restId, $cat['id'], $dishes);
										foreach($dishes as $dish)
										{
											if($dish['description'] == 'NOT_SET')
											{
												$dm->getDishDescription($dish['id'], $commentRow);
												$dish['description'] = $commentRow['comment']; 
											}	
											echo "<tr>";
											echo "<td width='20%'><span class='rest'><b>", $dish['name'], "</b></span></td><td width='70%'>", $dish['description'],"</td> <td width='10%'><b>";
											if($dish['price'] == 0);
											else
												echo $dish['price']; 
											echo '</b></td>' ; 
											echo "</tr>";
										}
									echo "</table></div>";
								}
								//get uncategorized dishes also
								$uDishes = array(); 
								$uResp = $dm->getUncategorizedRestuarantDishes($restId, $uDishes);
								if($uResp->code != 0)
								{
									echo "<div id='more_dishes' class='menuPage'><table id='menuTable'>";
										//get all dishes for given menu category
										foreach($uDishes as $dish)
										{
											if($dish['description'] == 'NOT_SET')
											{
												$dm->getDishDescription($dish['id'], $commentRow);
												$dish['description'] = $commentRow['comment']; 
											}
											echo "<tr>";
											echo "<td><span class='rest'><b>", $dish['name'], "</b></span></td><td>", $dish['description'],"</td> <td><b>",$dish['price'],'</b></td>' ; 
											echo "</tr>";
										}
									echo "</table></div>";
								}
							?>
							<p>*Prices are subject to change and may not be accurate.</p>
						</div>
					</div>
				</div>
				<div id="comments" style="display: none;">
                	<img id="loading" style="margin: 100px 0px 0px 150px;" src="./images/loading.gif"/>
                </div>
				
            </div>
            
        </div><!--content ends-->
        
        <div id="footer">
			<?php include 'footer.php'; ?>
        </div>
	</div>
<?php 
	if($restaurant->imgCount == 1)
	{	//no images hide the stage
?>
	</div>
<?php
}
?>
</body>
</html>

