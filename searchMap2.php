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
		var zoom = 3;
		var latlng = new google.maps.LatLng(37.4419, -100.1419);
		 
		var myOptions = {
		  zoom: zoom,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		map = new google.maps.Map(document.getElementById("searchMap"), myOptions);
		
		//initialize map
		markClientLocation();
		
		//go through restaurants in the DB and make a marker for each restaurant
		<?php
		if(isset($_POST['s_str']))
		{
			$start=0; 
			require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
			require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/imageModel.php';
			require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';
			$im = new imageModel(); 
			$rm = new RestModel(); 
			$response = new ResponseObject();
			$_POST['s_str'] = $response->sanitize($_POST['s_str']);
			$rests = array(); 
			$response = $rm->getRestaurantsGivenSearchStr($_POST['s_str'], $_POST['page']*6, 6, $rests);
			
			foreach ($rests as $row) {
				echo "markAddress(".$row['lat'].",".$row['lon'].",'".addslashes($row['name'])."');";
			}
		}
		?>
		/*
		google.maps.event.addListener(map, 'bounds_changed', function(){
			var mapBounds = map.getBounds();
			$('#searchBox').html('<br/> bound changed <br/>');
			for(var i=0; i<markers.length; i++)
			{
				if(mapBounds.contains(markers[i].getPosition()))
					$('#searchBox').append(markers[i].getTitle()+' and ');
			}
		});
		*/
	}
 
  function appendBootstrap() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "http://maps.google.com/maps/api/js?sensor=false&callback=handleApiReady";
    document.body.appendChild(script);
  }

  setTimeout("appendBootstrap()",500);   

</script>

<div id="searchMap" style="margin: 10px 0px 0px 10px; width: 572px; height: 297px; border: 1px solid orange;">
</div>