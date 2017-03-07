<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>

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
		}
		
	}

	function markAddress(lat, lon, name, content) {

		//create infowindow
		var contentString = "<div style=''>"+content+"</div>";  

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
			//map.setZoom(15);
			infowindow.setContent(contentString);
			infowindow.open(map,marker);
		});
		
  	}
	
	function handleApiReady() {
    // Initialize default values
		var zoom = 3;
		var latlng = new google.maps.LatLng(37.4419, -100.1419);
		 
		var myOptions = {
		  zoom: zoom,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById("searchMap"), myOptions);
		infowindow = new google.maps.InfoWindow({
			content: 'Hello'
		});
		//initialize map
		markClientLocation();
		
		//go through restaurants in the DB and make a marker for each restaurant
		<?php
		if(isset($_POST['type']))
		{
			$start=0; 
			require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
			require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';

			require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php';
			require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';

			require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 
			$im = new imageModel(); 
			$rm = new RestModel(); 
			$response = new ResponseObject();
			//$_POST['s_str'] = $response->sanitize($_POST['s_str']);
			$rests = array(); 
			$response = $rm->getNewRestaurants(0, 10, $rests); 
			
			
			
			
			//$response = $rm->getRestaurantsGivenSearchStr($_POST['s_str'], $_POST['page']*6, 6, $rests);
			
			foreach ($rests as $row) {
				$images = array();
				$imgResp = new ResponseObject();
				$imgResp = $rm->getNonDishImageForRestaurant($row['id'], $images[0]);
				$link = "<a href='http://".$_SERVER['HTTP_HOST'].'/hp/restProfile.php?restId='.$row['id']."'>";
				$imageLink = "<div class='thumbWrap'><img src='http://".$_SERVER['HTTP_HOST'].'/hp/uploaded/'.$im->getThumbPath($images[0]['imagePath'])."'/></div>";  
			$contentString = 
					"<table style='width: 250px;'>
					<tr>
						<td rowspan='4'>".$link.$imageLink."</a></td>
						<td>".$link."<span class='rest'>".$row['name']."</span></a></td>
					</tr>
					<tr>
						<td>"."<img src=".$rm->oRatingInStar($row['adminRating']/$row['noOfRatings'])." />"."</span></td>
					</tr>
					<tr>
						<td >".$row['address']."<br/> 
							".$row['city'].", ".$row['state']." - ".$row['zipcode']."</td>
					</tr>
					<tr>
						<td >".$row['phone']."</td>
					</tr>
				</table>";
				$sPattern = '/[\n\r]/m';
				$sReplace = '';
				$contentString = preg_replace( $sPattern, $sReplace, $contentString );

			
				echo "markAddress(".$row['lat'].",".$row['lon'].',"'.$row['name'].'","'.$contentString.'");';
			}
			
			
		}
		?>
		google.maps.event.trigger(markers[9], 'click');
	}
 
  function appendBootstrap() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=handleApiReady";
    document.body.appendChild(script);
  }

  setTimeout("appendBootstrap()",500);   

</script>
<div id="profile_title" style="width: 630px;">
Featured Places
</div>
<div id="searchMap" style="width: 100%; height: 90%; border: 1px solid orange;">
</div>