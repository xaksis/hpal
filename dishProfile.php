<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<?php include 'necessities.php'?>

<?php
	$gdId=1; 
	if(isset($_GET) && isset($_GET['gdId']))
		$gdId=$_GET['gdId']; 
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
	$rm = new RestModel(); 
	$im = new ImageModel(); 
	$dm = new DishModel();  
	$response = $dm->getGenericDishById($gdId, $gDish); 
	
?>
<title>Grub Pal - <?php echo $gDish['name']?></title>
<script type="text/javascript">

var map; 
					var geocoder;
					var infowindow;
					var markers = []; 
					var bounds; 
					function markClientLocation(){
						bounds = new google.maps.LatLngBounds(); 
						// If ClientLocation was filled in by the loader, use that info instead
						if (google.loader.ClientLocation) {
							
							var latlng = new google.maps.LatLng(google.loader.ClientLocation.latitude, google.loader.ClientLocation.longitude);
							bounds.extend(latlng);
							// Create client marker icon
							var homeIcon = "./images/map/home2.png";
							var homeShadow = "./images/map/homeShadow.png";
							//Create client marker
							var marker = new google.maps.Marker({
											position: latlng,
											map: map,
											title: 'Your Location',
											icon: homeIcon
										});
							 
							map.setCenter(latlng, 12);
							map.setZoom(13);
							infowindow = new google.maps.InfoWindow({
									  content: 'Hello world'
									});
						}
						
					}
						
					function markAddress(lat, lon, name) {

						//create infowindow
						var contentString = "<div style='text-align: center;'>"+name+"</div>";  

						var latlng = new google.maps.LatLng(lat, lon);
						bounds.extend(latlng);
						var restIcon = "./images/map/restaurant2.png";
						var marker = new google.maps.Marker({
										position: latlng,
										title: name,
										//icon: restIcon,
										map: map
									});
						markers.push(marker);
						map.fitBounds(bounds); 
						google.maps.event.addListener(marker, 'click', function() {
							map.setCenter(marker.getPosition());
							map.setZoom(15);
							infowindow.setContent(contentString);
							infowindow.open(map,marker);
						});
						
					}
					
				 function handleApiReady() {
					// Initialize default values
						var zoom = 13;
						var latlng = new google.maps.LatLng(37.4419, -100.1419);
						 
						var myOptions = {
						  zoom: zoom,
						  center: latlng,
						  mapTypeId: google.maps.MapTypeId.ROADMAP
						}
						map = new google.maps.Map(document.getElementById("map"), myOptions);
						//initialize map
						markClientLocation();
						
						<?php
							$resp2 = new ResponseObject(); 
							$dishes= array(); 
							//get a list of restaurants given a generic dish
							//to do this, we first get a list of dishes for each generic dish, and
							//for each dish we get the restaurant associated with it. 
							$resp2 = $dm->getDishesByGdishId(0, 5, $gdId, $dishes);

							$i=0;
							foreach ($dishes as $row) {
								$resp = new ResponseObject();
								$restaurant = new Restaurant();
								$resp = $rm->getRestaurantById($row['restaurant_id'], $restaurant); 
								echo "markAddress(".$restaurant->lat.",".$restaurant->lon.",'".addslashes($restaurant->name)."');";
							}
						?>
						
						
				}
				
				  function appendBootstrap() {
					var script = document.createElement("script");
					script.type = "text/javascript";
					script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=handleApiReady";
					document.body.appendChild(script);
				  }

				 setTimeout("appendBootstrap()",500); 


/*
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
*/
$(document).ready(function(){

	/*STAGE EFFECTS*/
	var page=0; 
	var totalPics=0; 
	
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
/*	
	//SLIDERS
	$("#user").slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 5,
			value: <?php echo 0; ?>,
			slide: function(event, ui){ return false; }
		});
	 $("#hpal").slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 5,
			value: <?php echo 0; ?>,
			slide: function(event, ui){ return false; }
		});
	/*SLIDERS END HERE*/
/*
	$("#right_button_link span").click(function(){
		 
		if(page+6>=totalPics)
			return;
		else
			page=page+6; 
		
		//call the next update
		$('#profile_stage').load("dish_profile_stage.php", {page: page, rest_id: <?php echo $restId; ?>},  function(response, status, xhr) {
			  if (status == "error") {
				page=page-6;
				if(page<0)
					page=5; 
			  }
			  
			  });
	});

	$("#left_button_link span").click(function(){
	
		if(page<=0)
			return;
		else
			page=page-6; 
		//call the next update
		$('#profile_stage').load("rest_profile_stage.php", {page: page, rest_id: <?php echo $restId; ?>},  function(response, status, xhr) {
			  if (status == "error") {
				page++;
				if(page>5)
					page=0; 
			  }
			 
			 });
	});
*/	
	
	//show pics in stage
	$("#profile_stage").load("dish_profile_stage.php", {page: page, gd_id: <?php echo $gdId; ?>});
	
	//show comments
	//$("#comments").load("rest_dishes.php", {restID: <?php echo 0; ?>});
	
	$("#commentsTitle li").click(function(){
		if($(this).attr("class") == 'active')
			return false; 
		else
		{
			//$('#comments').html("<img id='loading' style='margin: 100px 0px 0px 150px;' src='./images/loading.gif'/>"); 
			$("#commentsTitle li").each(function(){
				$(this).attr("class", "inactive");
			});
			$(this).attr("class", "active");
			var text = $(this).text(); 
			var urlToCall=""; 
			switch(text){
				case "Places":
					$("#about").hide();
					//$("#rests").html("<img id='loading' style='margin: 100px 0px 0px 150px;' src='./images/loading.gif'/>"); 
					$("#rests").show(); 
					//$("#rests").load("foundAt.php", {gd_id: <?php echo $gdId; ?>});
					break;
				case "About":
					$("#rests").hide();
					$("#about").show();
					break; 
				default:
					
					break;
			}
		}
	});
	
	$('#hideStage').click(function(){
		if($('#stage_blank').is(':visible'))
		{
			$('#about').addClass("shadow");
			$('#rests').addClass("shadow");
			$('#stage_blank').hide("slide",{direction: "up", speed: "slow"});
		}else{
			$('#rests').removeClass("shadow");
			$('#about').removeClass("shadow");
			$('#stage_blank').show("slide",{direction: "up", speed: "slow"});
		}
	});
	
	//initialize map
	//initialize();
	//code address
	//codeAddress();
		
 });      

</script>


</head>

<body>
	<div id="outerWrapper">
		<div id="header">
        	<?php include 'header.php' ?>
        </div>
        
        <div id="content">
        	<div id="stage_blank">
				<div id="profile_title">
					<?php $resp = new ResponseObject();  
					echo $gDish['name']; ?>
					<div style="float: right;">
						<?php $resp->likeButton('http://'.$_SERVER['HTTP_HOST'].'/hp/dishProfile.php?gdId='.$gdId); ?>
					</div>
				</div>
				
                <div id="nav_button">
                	<a id="left_button_link" href="#" onclick="return false;">
                    	<span>
                        </span>
                    </a>
                </div>
                
				<div id="profileStageWrapper">
					<div id="profile_stage">
					</div>
				</div>

                <div id="nav_button">
                	<a id="right_button_link" href="#" onclick="return false;">
                    	<span>
                        </span>
                    </a>
                </div>
                                
            </div><!--STAGE ENDS HERE-->
            <!--<a id="hideStage" href="javascript: void(0);">Hide Stage</a>-->
			<div id="belowStage">
            <div id="commentsWrapper">
            	<div id="commentsTitle">
                	<ul id="info_nav">
                    	<li class="active">About</li>
						<li>Places</li>
                    </ul>
                </div>
                <!--<div id="commentForm">
                </div>-->
                <div id="about">
					<h3>Description:</h3>
                	<?php echo $gDish['description']; ?>
					<br/>
					<br/>
					<h3>History & Origin:<h3/>
					<br/>
					<br/>
					<h3>Similar Dishes</h3>
					<div id="simHolder">
						<?php
							$sDishes = array();
							$sResponse = new ResponseObject(); 
							$sResp = $dm->getDishesByfamily(0, 8, $gDish['familyName'], $sDishes);
							$a=0;
							foreach ($sDishes as $row) {
							$a++; 
							if($a==5)
							{
								echo "<div id='clear'></div>";
							}
						?>
							<div style="float:left; margin:0px 0px 0px 10px;">
								<div class="thumbWrap" style="float:none;">
									<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/dishProfile.php?gdId='.$row['gDish_id']; ?>">
									<?php echo "<img src='http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($row['imagePath'])."'/>"; ?>
									</a>
								</div>
								<span style="color: #a1723f; font-weight: bold; text-align: center; display: block; width: 107px; "><?php echo $row['name'];?></span>
							</div>
						<?php
							}
						?>
					</div>
                </div>
				<div id="rests" style="display: none;">			
				<?php
					
					$resp2 = new ResponseObject(); 
					$dishes= array(); 
					//get a list of restaurants given a generic dish
					//to do this, we first get a list of dishes for each generic dish, and
					//for each dish we get the restaurant associated with it. 
					$resp2 = $dm->getDishesByGdishId(0, 5, $gdId, $dishes);
					$restList = array(); //to keep unique restaurant objects
					$idList = array(); //to keep unique ids
					foreach($dishes as $row)
					{
						$resp = new ResponseObject();
						$rest = new Restaurant();
						$resp = $rm->getRestaurantById($row['restaurant_id'], $rest);
						if(!in_array($rest->id, $idList))
						{
							//push unique id into idList
							array_push($idList, $rest->id); 
							array_push($restList, $rest); 
						}
					}
					
					$i=0;
					foreach ($restList as $restaurant) {
						$imgResp = new ResponseObject();
						$imgResp = $rm->getNonDishImageForRestaurant($restaurant->id, $image); 
					?>
						<div class="comment" id="<?php if($i%2){echo "commentEven";}else{echo "commentOdd";} ?>">
							<div class="thumbWrap">
								<a href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$restaurant->id; ?>">
								<?php
									if(isset($row['imagePath']))
									{
										echo "<img src='http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($image['imagePath'])."'/>"; 
									}
									else
									{
										echo "<img src='http://".$_SERVER['HTTP_HOST']."/hp/images/noRestaurantPic.png'/>"; 
									}
								?>
								</a>
							</div>
							<div id="userComment">
									<table style="float: left; width: 190px;" >
										<tr>
											
											<td><span style="color: #a1723f; font-weight: bold;"><?php echo $restaurant->name;?></span></td>
											<td></td>
											<td><img src='<?php echo $rm->ratingInStar($restaurant->adminRating/$restaurant->noOfRatings); ?>' /></td>
										</tr>
										<tr>
											
											<td colspan="3"><?php echo $restaurant->address; ?> <br/> 
												<?php echo $restaurant->city; ?>, <?php echo $restaurant->state; ?> - 
												<?php echo $restaurant->zipcode; ?>
											</td>
										</tr>
										<tr>
											<td colspan="3"><?php echo $restaurant->phone; ?></td>
										</tr>
										<tr>
											<td colspan="3">
												<?php 	$categories = array(); 
													$rm->getCategoriesForRestaurant($row['restaurant_id'], $categories);
													$count=0;
													foreach($categories as $category){
														if($count!=0)
															echo ", "; 
														echo $category['name'];
														$count++; 
													}							
												?>
											</td>
										</tr>
									</table>
									
							</div>
						</div>
					<?php
						$i++; 
					}?>
                </div>
            </div>
            
            <div id="restInfoWrapper">
            	
                <div id="mapWrapper">
                	<div id="map" style="height: 550px; width: 250px;">
                    </div>
                    
                </div>
            </div>
         
		</div> <!-- below stage -->
		 
        </div><!--content ends-->
        
        <div id="footer">
			<?php include 'footer.php'; ?>
        </div>
	</div>

</body>
</html>

